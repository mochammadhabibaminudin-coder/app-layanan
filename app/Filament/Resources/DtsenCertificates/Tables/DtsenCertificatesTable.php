<?php

namespace App\Filament\Resources\DtsenCertificates\Tables;

use App\Enums\ApprovalDecision;
use App\Enums\ApprovalStep;
use App\Enums\ServiceRequestStatus;
use App\Models\NumberSequence;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class DtsenCertificatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('certificate_number')
                    ->label('No. Surat SK')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Belum terbit')
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('serviceRequest.request_number')
                    ->label('No. Tiket')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('subject_name')
                    ->label('Nama yang Diterangkan')
                    ->searchable()
                    ->description(fn ($record) => 'NIK: '.$record->subject_nik.' ('.$record->relationship_to_applicant.')'),
                TextColumn::make('purpose.name')
                    ->label('Tujuan Penggunaan')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_registered')
                    ->label('Terdaftar SIKS-NG')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('decile')
                    ->label('Desil')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state <= 2 => 'success',
                        $state <= 4 => 'info',
                        $state <= 5 => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => $state ? 'Desil '.$state : '-')
                    ->sortable(),
                TextColumn::make('issued_at')
                    ->label('Tgl Terbit')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->placeholder('Belum terbit'),
                TextColumn::make('valid_until')
                    ->label('Berlaku s.d.')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('verification_code')
                    ->label('Kode Verifikasi QR')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('signer.name')
                    ->label('Pejabat Penandatangan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('checker.name')
                    ->label('Petugas Cek SIKS-NG')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('dtsen_purpose_id')
                    ->label('Tujuan Penggunaan')
                    ->relationship('purpose', 'name'),
                TernaryFilter::make('is_registered')
                    ->label('Status di SIKS-NG')
                    ->placeholder('Semua')
                    ->trueLabel('Terdaftar')
                    ->falseLabel('Tidak Terdaftar'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                ActionGroup::make([
                    Action::make('check_siks_ng')
                        ->label('Catat Hasil Cek SIKS-NG')
                        ->icon(Heroicon::OutlinedMagnifyingGlassCircle)
                        ->color('info')
                        ->schema([
                            Toggle::make('is_registered')
                                ->label('Warga Terdaftar di SIKS-NG / DTSEN')
                                ->default(fn ($record) => $record->is_registered ?? true)
                                ->required(),
                            TextInput::make('decile')
                                ->label('Peringkat Desil (1–10)')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(10)
                                ->default(fn ($record) => $record->decile)
                                ->helperText(fn ($record) => "Batas maksimal untuk {$record->purpose?->name}: Desil {$record->purpose?->max_decile}")
                                ->required(),
                        ])
                        ->action(function ($record, array $data): void {
                            $maxDecile = $record->purpose?->max_decile ?? 10;
                            $exceeds = (int) $data['decile'] > $maxDecile;

                            $record->update([
                                'is_registered' => $data['is_registered'],
                                'decile' => $data['decile'],
                                'checked_at' => now(),
                                'checker_id' => auth()->id(),
                            ]);

                            $note = sprintf(
                                'Cek SIKS-NG: %s, Desil: %d (Maks: %d)%s',
                                $data['is_registered'] ? 'Terdaftar' : 'Tidak Terdaftar',
                                $data['decile'],
                                $maxDecile,
                                $exceeds ? ' — PERINGATAN: Desil melebihi batas maksimal!' : ' — Memenuhi syarat'
                            );

                            if ($record->serviceRequest) {
                                $record->serviceRequest->transitionTo(ServiceRequestStatus::DataVerification, $note);
                            }

                            if ($exceeds) {
                                Notification::make()
                                    ->title('Peringatan: Desil Melebihi Ketentuan!')
                                    ->body("Desil {$data['decile']} melebihi batas maksimal Desil {$maxDecile} untuk tujuan {$record->purpose?->name}.")
                                    ->warning()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Hasil Cek SIKS-NG Disimpan')
                                    ->success()
                                    ->send();
                            }
                        }),

                    Action::make('approve_paraf_kabid')
                        ->label('Paraf Kabid')
                        ->icon(Heroicon::OutlinedPencilSquare)
                        ->color('purple')
                        ->schema([
                            Select::make('decision')
                                ->label('Pemberian Paraf')
                                ->options([
                                    ApprovalDecision::Approved->value => 'Paraf Disetujui (Lanjut ke Kadis)',
                                    ApprovalDecision::Returned->value => 'Kembalikan (Perlu Perbaikan)',
                                ])
                                ->required(),
                            Textarea::make('notes')
                                ->label('Catatan Paraf'),
                        ])
                        ->action(function ($record, array $data): void {
                            $record->approvals()->updateOrCreate(
                                ['step' => ApprovalStep::Kabid],
                                [
                                    'approver_id' => auth()->id(),
                                    'decision' => $data['decision'],
                                    'notes' => $data['notes'] ?? null,
                                    'decided_at' => now(),
                                ]
                            );

                            Notification::make()
                                ->title('Paraf Kepala Bidang berhasil disimpan')
                                ->success()
                                ->send();
                        }),

                    Action::make('issue_certificate')
                        ->label('Terbitkan SK DTSEN (Tanda Tangan Kadis)')
                        ->icon(Heroicon::OutlinedDocumentCheck)
                        ->color('success')
                        ->visible(fn ($record) => blank($record->certificate_number))
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Penerbitan Surat Keterangan DTSEN')
                        ->modalDescription('Sistem akan membuat nomor surat resmi dinas dan kode verifikasi QR untuk dokumen ini.')
                        ->action(function ($record): void {
                            if (! $record->is_registered) {
                                Notification::make()
                                    ->title('Gagal Menerbitkan SK')
                                    ->body('Pemohon tidak terdaftar di SIKS-NG/DTSEN.')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $maxDecile = $record->purpose?->max_decile ?? 10;
                            if ($record->decile > $maxDecile) {
                                Notification::make()
                                    ->title('Gagal Menerbitkan SK')
                                    ->body("Desil {$record->decile} melebihi batas maksimal {$maxDecile} untuk tujuan ini.")
                                    ->danger()
                                    ->send();

                                return;
                            }

                            // Generate official certificate number: 400.9/{seq}/409.103/{year}
                            $seqStr = NumberSequence::generateNext('SK-DTSEN', now(), 3);
                            $parts = explode('-', $seqStr);
                            $rawSeq = end($parts);
                            $certNumber = sprintf('400.9/%s/409.103/%s', $rawSeq, now()->format('Y'));

                            // Generate verification code
                            $verificationCode = 'DTSEN-'.strtoupper(Str::random(10));

                            // Calculate valid until
                            $validDays = $record->purpose?->validity_days;
                            $validUntil = $validDays ? now()->addDays($validDays)->toDateString() : null;

                            $record->update([
                                'certificate_number' => $certNumber,
                                'verification_code' => $verificationCode,
                                'issued_at' => now(),
                                'valid_until' => $validUntil,
                                'signer_id' => auth()->id(),
                            ]);

                            // Record Kadis approval
                            $record->approvals()->updateOrCreate(
                                ['step' => ApprovalStep::Kadis],
                                [
                                    'approver_id' => auth()->id(),
                                    'decision' => ApprovalDecision::Approved,
                                    'notes' => 'Surat Keterangan DTSEN telah disetujui dan diterbitkan secara resmi.',
                                    'decided_at' => now(),
                                ]
                            );

                            if ($record->serviceRequest) {
                                $record->serviceRequest->transitionTo(ServiceRequestStatus::Issued, "SK DTSEN diterbitkan dengan No. {$certNumber}");
                            }

                            Notification::make()
                                ->title('Surat Keterangan DTSEN Berhasil Diterbitkan!')
                                ->body("Nomor Surat: {$certNumber}")
                                ->success()
                                ->send();
                        }),
                ])->label('Aksi Alur SK'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
