<?php

namespace App\Filament\Resources\Referrals\Tables;

use App\Enums\ReferralStatus;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ReferralsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('referral_date', 'desc')
            ->columns([
                TextColumn::make('referral_number')
                    ->label('No. Rujukan')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('rehabilitationCase.case_number')
                    ->label('No. Kasus')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('rehabilitationCase.client.name')
                    ->label('Nama Klien')
                    ->searchable()
                    ->description(fn ($record) => $record->rehabilitationCase?->client?->category?->name ?? '-'),
                TextColumn::make('institution.name')
                    ->label('Lembaga Tujuan')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->institution?->type ?? ''),
                TextColumn::make('referral_date')
                    ->label('Tgl Rujukan')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof ReferralStatus ? $state->label() : (ReferralStatus::tryFrom((string) $state)?->label() ?? $state))
                    ->color(fn ($state) => $state instanceof ReferralStatus ? $state->color() : (ReferralStatus::tryFrom((string) $state)?->color() ?? 'gray')),
                TextColumn::make('officer.name')
                    ->label('Petugas')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('completed_at')
                    ->label('Tgl Selesai')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Rujukan')
                    ->options(collect(ReferralStatus::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])),
                SelectFilter::make('referral_institution_id')
                    ->label('Lembaga Tujuan')
                    ->relationship('institution', 'name'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                ActionGroup::make([
                    Action::make('send_referral')
                        ->label('Kirim Berkas Rujukan')
                        ->icon(Heroicon::OutlinedPaperAirplane)
                        ->color('info')
                        ->visible(fn ($record) => $record->status === ReferralStatus::Draft)
                        ->requiresConfirmation()
                        ->modalHeading('Kirim Surat & Berkas Rujukan ke Lembaga')
                        ->action(function ($record): void {
                            $record->transitionTo(ReferralStatus::Sent, 'Surat rujukan dikirimkan ke pihak lembaga tujuan');

                            Notification::make()
                                ->title('Rujukan berhasil dikirim')
                                ->success()
                                ->send();
                        }),

                    Action::make('accept_referral')
                        ->label('Konfirmasi Diterima Lembaga')
                        ->icon(Heroicon::OutlinedCheck)
                        ->color('purple')
                        ->visible(fn ($record) => $record->status === ReferralStatus::Sent)
                        ->schema([
                            Textarea::make('notes')
                                ->label('Catatan Penerimaan Lembaga')
                                ->placeholder('Contoh: Berkas diterima oleh admin panti / dijadwalkan masuk asrama'),
                        ])
                        ->action(function ($record, array $data): void {
                            $record->transitionTo(ReferralStatus::Accepted, $data['notes'] ?? 'Diterima oleh lembaga tujuan');

                            Notification::make()
                                ->title('Konfirmasi penerimaan rujukan dicatat')
                                ->success()
                                ->send();
                        }),

                    Action::make('start_in_service')
                        ->label('Mulai Pelayanan di Lembaga')
                        ->icon(Heroicon::OutlinedPlay)
                        ->color('warning')
                        ->visible(fn ($record) => $record->status === ReferralStatus::Accepted)
                        ->action(function ($record): void {
                            $record->transitionTo(ReferralStatus::InService, 'Klien mulai menjalani pelayanan/rehabilitasi di lembaga rujukan');

                            Notification::make()
                                ->title('Status penanganan diperbarui: Dalam Pelayanan')
                                ->success()
                                ->send();
                        }),

                    Action::make('complete_referral')
                        ->label('Selesaikan Rujukan')
                        ->icon(Heroicon::OutlinedCheckBadge)
                        ->color('success')
                        ->visible(fn ($record) => in_array($record->status, [ReferralStatus::InService, ReferralStatus::Accepted]))
                        ->schema([
                            Textarea::make('service_result')
                                ->label('Hasil Akhir Pelayanan dari Lembaga')
                                ->required()
                                ->placeholder('Contoh: Klien telah menyelesaikan rehabilitasi selama 3 bulan dan siap terminasi/pulang'),
                        ])
                        ->action(function ($record, array $data): void {
                            $record->service_result = $data['service_result'];
                            $record->completed_at = now();
                            $record->transitionTo(ReferralStatus::Completed, $data['service_result']);

                            Notification::make()
                                ->title('Pelayanan rujukan berhasil diselesaikan')
                                ->success()
                                ->send();
                        }),

                    Action::make('decline_referral')
                        ->label('Tolak / Batalkan Rujukan')
                        ->icon(Heroicon::OutlinedXCircle)
                        ->color('danger')
                        ->visible(fn ($record) => ! in_array($record->status, [ReferralStatus::Completed, ReferralStatus::Declined, ReferralStatus::Cancelled]))
                        ->schema([
                            Select::make('decision')
                                ->label('Keputusan')
                                ->options([
                                    ReferralStatus::Declined->value => 'Ditolak Lembaga (Kapasitas Penuh / Tidak Sesuai Kriteria)',
                                    ReferralStatus::Cancelled->value => 'Dibatalkan oleh Dinas Sosial',
                                ])
                                ->required(),
                            Textarea::make('reason')
                                ->label('Alasan Penolakan / Pembatalan')
                                ->required(),
                        ])
                        ->action(function ($record, array $data): void {
                            $record->service_result = $data['reason'];
                            $record->transitionTo($data['decision'], $data['reason']);

                            Notification::make()
                                ->title('Rujukan dibatalkan / ditolak')
                                ->danger()
                                ->send();
                        }),
                ])->label('Aksi Rujukan'),
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
