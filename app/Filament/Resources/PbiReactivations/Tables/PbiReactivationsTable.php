<?php

namespace App\Filament\Resources\PbiReactivations\Tables;

use App\Enums\MinistryDecision;
use App\Enums\PbiReactivationReason;
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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PbiReactivationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('recommendation_number')
                    ->label('No. Rekomendasi')
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
                TextColumn::make('participant_name')
                    ->label('Nama Peserta BPJS')
                    ->searchable()
                    ->description(fn ($record) => 'NIK: '.$record->participant_nik.' | KIS: '.$record->bpjs_card_number),
                TextColumn::make('reason')
                    ->label('Alasan Reaktivasi')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof PbiReactivationReason ? $state->label() : (PbiReactivationReason::tryFrom((string) $state)?->label() ?? $state))
                    ->color(fn ($state) => $state === PbiReactivationReason::Emergency ? 'danger' : 'info'),
                TextColumn::make('decile')
                    ->label('Desil')
                    ->badge()
                    ->placeholder('-')
                    ->color(fn ($state) => match (true) {
                        $state <= 2 => 'success',
                        $state <= 4 => 'info',
                        default => 'warning',
                    }),
                TextColumn::make('proposed_to_ministry_at')
                    ->label('Input SIKS-NG')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->placeholder('Belum diusulkan'),
                TextColumn::make('ministry_decision')
                    ->label('Keputusan Kemensos')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof MinistryDecision ? $state->label() : (MinistryDecision::tryFrom((string) $state)?->label() ?? $state))
                    ->color(fn ($state) => $state instanceof MinistryDecision ? $state->color() : (MinistryDecision::tryFrom((string) $state)?->color() ?? 'gray')),
                TextColumn::make('reactivated_date')
                    ->label('Tgl Aktif Kembali')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('health_facility_name')
                    ->label('Fasilitas Kesehatan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('signer.name')
                    ->label('Penandatangan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('reason')
                    ->label('Alasan Reaktivasi')
                    ->options(collect(PbiReactivationReason::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])),
                SelectFilter::make('ministry_decision')
                    ->label('Keputusan Kemensos')
                    ->options(collect(MinistryDecision::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                ActionGroup::make([
                    Action::make('verify_eligibility')
                        ->label('Verifikasi Kelayakan (Dinsos)')
                        ->icon(Heroicon::OutlinedShieldCheck)
                        ->color('info')
                        ->schema([
                            TextInput::make('decile')
                                ->label('Peringkat Desil (1–10)')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(10)
                                ->default(fn ($record) => $record->decile),
                            Textarea::make('eligibility_notes')
                                ->label('Catatan Hasil Verifikasi Kelayakan')
                                ->required()
                                ->default(fn ($record) => $record->eligibility_notes),
                        ])
                        ->action(function ($record, array $data): void {
                            $record->update([
                                'decile' => $data['decile'] ?? $record->decile,
                                'eligibility_notes' => $data['eligibility_notes'],
                            ]);

                            if ($record->serviceRequest) {
                                $record->serviceRequest->transitionTo(
                                    ServiceRequestStatus::EligibilityVerification,
                                    "Verifikasi kelayakan: Desil {$record->decile}. {$data['eligibility_notes']}"
                                );
                            }

                            Notification::make()
                                ->title('Verifikasi kelayakan berhasil disimpan')
                                ->success()
                                ->send();
                        }),

                    Action::make('issue_recommendation')
                        ->label('Terbitkan Surat Rekomendasi')
                        ->icon(Heroicon::OutlinedDocumentText)
                        ->color('purple')
                        ->visible(fn ($record) => blank($record->recommendation_number))
                        ->requiresConfirmation()
                        ->modalHeading('Terbitkan Surat Rekomendasi Reaktivasi PBI-JK')
                        ->modalDescription('Sistem akan membuat nomor surat rekomendasi dinas resmi untuk pengusulan ke Kemensos.')
                        ->action(function ($record): void {
                            $seqStr = NumberSequence::generateNext('REK-PBI', now(), 3);
                            $parts = explode('-', $seqStr);
                            $rawSeq = end($parts);
                            $rekNumber = sprintf('400.9/REK-%s/409.103/%s', $rawSeq, now()->format('Y'));

                            $record->update([
                                'recommendation_number' => $rekNumber,
                                'recommendation_issued_at' => now(),
                                'signer_id' => auth()->id(),
                            ]);

                            if ($record->serviceRequest) {
                                $record->serviceRequest->transitionTo(
                                    ServiceRequestStatus::RecommendationIssued,
                                    "Surat rekomendasi terbit: {$rekNumber}"
                                );
                            }

                            Notification::make()
                                ->title('Surat Rekomendasi Berhasil Diterbitkan')
                                ->body("No. Rekomendasi: {$rekNumber}")
                                ->success()
                                ->send();
                        }),

                    Action::make('propose_to_ministry')
                        ->label('Catat Pengusulan SIKS-NG (Kemensos)')
                        ->icon(Heroicon::OutlinedPaperAirplane)
                        ->color('warning')
                        ->visible(fn ($record) => filled($record->recommendation_number) && blank($record->proposed_to_ministry_at))
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Pengusulan ke SIKS-NG Kemensos')
                        ->modalDescription('Pastikan data usulan telah berhasil diinput ke portal SIKS-NG Kementerian Sosial.')
                        ->action(function ($record): void {
                            $record->update([
                                'proposed_to_ministry_at' => now(),
                            ]);

                            if ($record->serviceRequest) {
                                $record->serviceRequest->transitionTo(
                                    ServiceRequestStatus::ProposedToMinistry,
                                    'Usulan reaktivasi telah diinput ke SIKS-NG Kemensos'
                                );
                            }

                            Notification::make()
                                ->title('Tanggal pengusulan SIKS-NG berhasil dicatat')
                                ->success()
                                ->send();
                        }),

                    Action::make('record_ministry_decision')
                        ->label('Catat Keputusan Kemensos')
                        ->icon(Heroicon::OutlinedBuildingLibrary)
                        ->color('info')
                        ->visible(fn ($record) => filled($record->proposed_to_ministry_at) && $record->ministry_decision === MinistryDecision::Pending)
                        ->schema([
                            Select::make('ministry_decision')
                                ->label('Keputusan Kementerian Sosial')
                                ->options([
                                    MinistryDecision::Approved->value => 'Disetujui Kemensos',
                                    MinistryDecision::Rejected->value => 'Ditolak Kemensos',
                                ])
                                ->required(),
                            Textarea::make('notes')
                                ->label('Catatan Keputusan / Alasan Penolakan'),
                        ])
                        ->action(function ($record, array $data): void {
                            $isApproved = $data['ministry_decision'] === MinistryDecision::Approved->value;

                            $record->update([
                                'ministry_decision' => $data['ministry_decision'],
                                'ministry_decided_at' => now(),
                            ]);

                            $targetStatus = $isApproved ? ServiceRequestStatus::MinistryApproved : ServiceRequestStatus::MinistryRejected;
                            $note = sprintf('Keputusan Kemensos: %s. %s', $isApproved ? 'Disetujui' : 'Ditolak', $data['notes'] ?? '');

                            if ($record->serviceRequest) {
                                $record->serviceRequest->transitionTo($targetStatus, $note);
                            }

                            Notification::make()
                                ->title('Keputusan Kemensos berhasil disimpan')
                                ->success()
                                ->send();
                        }),

                    Action::make('confirm_reactivated')
                        ->label('Konfirmasi Aktif Kembali di BPJS')
                        ->icon(Heroicon::OutlinedCheckBadge)
                        ->color('success')
                        ->visible(fn ($record) => $record->ministry_decision === MinistryDecision::Approved && blank($record->reactivated_date))
                        ->schema([
                            DatePicker::make('reactivated_date')
                                ->label('Tanggal Kartu Aktif Kembali di BPJS Kesehatan')
                                ->default(now())
                                ->required(),
                        ])
                        ->action(function ($record, array $data): void {
                            $record->update([
                                'reactivated_date' => $data['reactivated_date'],
                            ]);

                            if ($record->serviceRequest) {
                                $record->serviceRequest->service_result = "Kepesertaan PBI-JK aktif kembali di BPJS Kesehatan per tanggal {$data['reactivated_date']}.";
                                $record->serviceRequest->completed_at = now();
                                $record->serviceRequest->transitionTo(
                                    ServiceRequestStatus::Reactivated,
                                    "Kepesertaan aktif per {$data['reactivated_date']}"
                                );
                            }

                            Notification::make()
                                ->title('Kepesertaan Berhasil Diaktifkan!')
                                ->body('Status layanan kini selesai (Reactivated).')
                                ->success()
                                ->send();
                        }),
                ])->label('Aksi Reaktivasi'),
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
