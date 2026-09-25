<?php

namespace App\Filament\Resources\ServiceRequests\Tables;

use App\Enums\ServiceRequestStatus;
use App\Models\User;
use App\Models\WorkUnit;
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
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ServiceRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('submitted_at', 'desc')
            ->columns([
                TextColumn::make('request_number')
                    ->label('No. Tiket')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('serviceType.name')
                    ->label('Jenis Layanan')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('applicant_name')
                    ->label('Nama Pemohon')
                    ->searchable()
                    ->description(fn ($record) => 'NIK: '.$record->applicant_nik),
                TextColumn::make('village.name')
                    ->label('Desa/Kelurahan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('No. HP / WA')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof ServiceRequestStatus ? $state->label() : (ServiceRequestStatus::tryFrom((string) $state)?->label() ?? $state))
                    ->color(fn ($state) => $state instanceof ServiceRequestStatus ? $state->color() : (ServiceRequestStatus::tryFrom((string) $state)?->color() ?? 'gray')),
                IconColumn::make('is_priority')
                    ->label('Prioritas')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('submitted_at')
                    ->label('Tgl Pengajuan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('officer.name')
                    ->label('Petugas')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('workUnit.name')
                    ->label('Unit Kerja')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('completed_at')
                    ->label('Tgl Selesai')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Pengajuan')
                    ->options(collect(ServiceRequestStatus::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])),
                SelectFilter::make('service_type_id')
                    ->label('Jenis Layanan')
                    ->relationship('serviceType', 'name'),
                SelectFilter::make('is_priority')
                    ->label('Prioritas')
                    ->options([
                        '1' => 'Prioritas (Darurat)',
                        '0' => 'Reguler',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                ActionGroup::make([
                    Action::make('check_document')
                        ->label('Periksa Kelengkapan Berkas')
                        ->icon(Heroicon::OutlinedDocumentMagnifyingGlass)
                        ->color('warning')
                        ->visible(fn ($record) => in_array($record->status, [ServiceRequestStatus::Submitted, ServiceRequestStatus::RevisionRequested]))
                        ->schema([
                            Select::make('decision')
                                ->label('Hasil Pemeriksaan Berkas')
                                ->options([
                                    'complete' => 'Berkas Lengkap (Lanjut Verifikasi)',
                                    'revision' => 'Perlu Perbaikan / Revisi Dokumen',
                                ])
                                ->required(),
                            Textarea::make('notes')
                                ->label('Catatan Pemeriksaan')
                                ->placeholder('Tuliskan berkas yang perlu diperbaiki jika ada')
                                ->required(),
                        ])
                        ->action(function ($record, array $data): void {
                            if ($data['decision'] === 'complete') {
                                $record->verification_result = $data['notes'];
                                $record->transitionTo(ServiceRequestStatus::DocumentCheck, $data['notes']);
                            } else {
                                $record->verification_result = $data['notes'];
                                $record->transitionTo(ServiceRequestStatus::RevisionRequested, $data['notes']);
                            }

                            Notification::make()
                                ->title('Pemeriksaan berkas disimpan')
                                ->success()
                                ->send();
                        }),

                    Action::make('verify_data')
                        ->label('Verifikasi Data (SIKS-NG)')
                        ->icon(Heroicon::OutlinedCheckBadge)
                        ->color('info')
                        ->visible(fn ($record) => in_array($record->status, [ServiceRequestStatus::DocumentCheck, ServiceRequestStatus::Submitted]))
                        ->schema([
                            Textarea::make('verification_result')
                                ->label('Hasil Cek Data / SIKS-NG')
                                ->required()
                                ->default(fn ($record) => $record->verification_result),
                        ])
                        ->action(function ($record, array $data): void {
                            $targetStatus = $record->serviceType?->code === 'PBI'
                                ? ServiceRequestStatus::EligibilityVerification
                                : ServiceRequestStatus::DataVerification;

                            $record->verification_result = $data['verification_result'];
                            $record->transitionTo($targetStatus, $data['verification_result']);

                            Notification::make()
                                ->title('Verifikasi data disimpan')
                                ->success()
                                ->send();
                        }),

                    Action::make('submit_for_approval')
                        ->label('Ajukan Persetujuan Pejabat')
                        ->icon(Heroicon::OutlinedPaperAirplane)
                        ->color('purple')
                        ->visible(fn ($record) => in_array($record->status, [ServiceRequestStatus::DataVerification, ServiceRequestStatus::EligibilityVerification]))
                        ->requiresConfirmation()
                        ->modalHeading('Kirim ke Antrean Tanda Tangan / Persetujuan')
                        ->modalDescription('Pastikan data verifikasi SIKS-NG dan draf surat sudah lengkap sebelum diteruskan.')
                        ->action(function ($record): void {
                            $record->transitionTo(ServiceRequestStatus::AwaitingApproval, 'Diteruskan ke pejabat penandatangan');

                            Notification::make()
                                ->title('Pengajuan diteruskan ke pejabat penandatangan')
                                ->success()
                                ->send();
                        }),

                    Action::make('complete_service')
                        ->label('Selesaikan Layanan')
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->color('success')
                        ->visible(fn ($record) => ! in_array($record->status, [ServiceRequestStatus::Completed, ServiceRequestStatus::Rejected]))
                        ->schema([
                            Textarea::make('service_result')
                                ->label('Hasil Akhir Pelayanan')
                                ->required()
                                ->placeholder('Contoh: Surat keterangan telah diserahkan / kepesertaan berhasil diaktifkan'),
                        ])
                        ->action(function ($record, array $data): void {
                            $record->service_result = $data['service_result'];
                            $record->completed_at = now();
                            $record->transitionTo(ServiceRequestStatus::Completed, $data['service_result']);

                            Notification::make()
                                ->title('Layanan berhasil diselesaikan')
                                ->success()
                                ->send();
                        }),

                    Action::make('reject_service')
                        ->label('Tolak Pengajuan')
                        ->icon(Heroicon::OutlinedXCircle)
                        ->color('danger')
                        ->visible(fn ($record) => ! in_array($record->status, [ServiceRequestStatus::Completed, ServiceRequestStatus::Rejected]))
                        ->schema([
                            Textarea::make('rejection_reason')
                                ->label('Alasan Penolakan')
                                ->required()
                                ->placeholder('Tuliskan alasan penolakan secara jelas untuk disampaikan kepada pemohon'),
                        ])
                        ->action(function ($record, array $data): void {
                            $record->rejection_reason = $data['rejection_reason'];
                            $record->completed_at = now();
                            $record->transitionTo(ServiceRequestStatus::Rejected, $data['rejection_reason']);

                            Notification::make()
                                ->title('Pengajuan layanan ditolak')
                                ->danger()
                                ->send();
                        }),

                    Action::make('dispatch_disposition')
                        ->label('Disposisikan')
                        ->icon(Heroicon::OutlinedArrowUturnRight)
                        ->color('gray')
                        ->schema([
                            Select::make('to_work_unit_id')
                                ->label('Disposisi ke Unit Kerja')
                                ->options(WorkUnit::pluck('name', 'id'))
                                ->required(),
                            Select::make('to_user_id')
                                ->label('Petugas Penerima (Opsional)')
                                ->options(User::pluck('name', 'id')),
                            Textarea::make('instructions')
                                ->label('Instruksi Disposisi')
                                ->required(),
                        ])
                        ->action(function ($record, array $data): void {
                            $record->dispositions()->create([
                                'from_user_id' => auth()->id(),
                                'to_work_unit_id' => $data['to_work_unit_id'],
                                'to_user_id' => $data['to_user_id'] ?? null,
                                'instructions' => $data['instructions'],
                                'disposed_at' => now(),
                            ]);

                            $record->update([
                                'work_unit_id' => $data['to_work_unit_id'],
                                'officer_id' => $data['to_user_id'] ?? $record->officer_id,
                            ]);

                            Notification::make()
                                ->title('Disposisi berhasil dikirim')
                                ->success()
                                ->send();
                        }),
                ])->label('Aksi Alur Kerja'),
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
