<?php

namespace App\Filament\Resources\RehabilitationCases\Tables;

use App\Enums\HandlingType;
use App\Enums\RehabilitationCaseStatus;
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

class RehabilitationCasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('received_at', 'desc')
            ->columns([
                TextColumn::make('case_number')
                    ->label('No. Kasus')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('client.name')
                    ->label('Nama Klien')
                    ->searchable()
                    ->description(fn ($record) => $record->client?->category?->name.' — '.($record->client?->village?->name ?? '-')),
                TextColumn::make('handling_type')
                    ->label('Jenis Penanganan')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof HandlingType ? $state->label() : (HandlingType::tryFrom((string) $state)?->label() ?? $state)),
                TextColumn::make('status')
                    ->label('Status Kasus')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof RehabilitationCaseStatus ? $state->label() : (RehabilitationCaseStatus::tryFrom((string) $state)?->label() ?? $state))
                    ->color(fn ($state) => $state instanceof RehabilitationCaseStatus ? $state->color() : (RehabilitationCaseStatus::tryFrom((string) $state)?->color() ?? 'gray')),
                TextColumn::make('officer.name')
                    ->label('Pekerja Sosial')
                    ->searchable(),
                TextColumn::make('received_at')
                    ->label('Tgl Diterima')
                    ->dateTime('d M Y')
                    ->sortable(),
                TextColumn::make('closed_at')
                    ->label('Tgl Ditutup')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Kasus')
                    ->options(collect(RehabilitationCaseStatus::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])),
                SelectFilter::make('handling_type')
                    ->label('Jenis Penanganan')
                    ->options(collect(HandlingType::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                ActionGroup::make([
                    Action::make('advance_stage')
                        ->label('Ubah Tahapan Kasus')
                        ->icon(Heroicon::OutlinedForward)
                        ->color('info')
                        ->visible(fn ($record) => $record->status !== RehabilitationCaseStatus::Closed)
                        ->schema([
                            Select::make('status')
                                ->label('Pindah ke Tahapan')
                                ->options([
                                    RehabilitationCaseStatus::Assessment->value => '1. Assessment (Pemeriksaan Kebutuhan)',
                                    RehabilitationCaseStatus::ServicePlanning->value => '2. Perencanaan Pelayanan (Service Planning)',
                                    RehabilitationCaseStatus::InService->value => '3. Dalam Pelayanan / Rujukan (In Service)',
                                    RehabilitationCaseStatus::Monitoring->value => '4. Monitoring Perkembangan',
                                ])
                                ->required(),
                            Textarea::make('notes')
                                ->label('Catatan Perkembangan Tahapan')
                                ->placeholder('Ringkasan progres penanganan kasus'),
                        ])
                        ->action(function ($record, array $data): void {
                            $record->transitionTo($data['status'], $data['notes'] ?? null);

                            Notification::make()
                                ->title('Tahapan kasus berhasil diperbarui')
                                ->success()
                                ->send();
                        }),

                    Action::make('close_case')
                        ->label('Tutup Kasus (Selesai/Terminasi)')
                        ->icon(Heroicon::OutlinedCheckBadge)
                        ->color('success')
                        ->visible(fn ($record) => $record->status !== RehabilitationCaseStatus::Closed)
                        ->schema([
                            Textarea::make('handling_result')
                                ->label('Hasil Akhir Pelayanan / Terminasi')
                                ->placeholder('Jelaskan hasil pelayanan, kemandirian klien, reintegrasi keluarga, atau rujukan yang tuntas')
                                ->required(),
                        ])
                        ->action(function ($record, array $data): void {
                            $record->handling_result = $data['handling_result'];
                            $record->closed_at = now();
                            $record->transitionTo(RehabilitationCaseStatus::Closed, $data['handling_result']);

                            Notification::make()
                                ->title('Kasus Rehabilitasi Sosial Resmi Ditutup')
                                ->success()
                                ->send();
                        }),
                ])->label('Aksi Kasus'),
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
