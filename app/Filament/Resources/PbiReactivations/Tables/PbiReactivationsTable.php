<?php

namespace App\Filament\Resources\PbiReactivations\Tables;

use App\Enums\MinistryDecision;
use App\Enums\PbiReactivationReason;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
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
                    ->description(fn ($record) => 'No. Kartu: '.$record->bpjs_card_number),
                TextColumn::make('reason')
                    ->label('Alasan Reaktivasi')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof PbiReactivationReason ? $state->label() : (PbiReactivationReason::tryFrom((string) $state)?->label() ?? $state)),
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
