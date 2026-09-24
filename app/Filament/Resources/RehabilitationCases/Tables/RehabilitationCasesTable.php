<?php

namespace App\Filament\Resources\RehabilitationCases\Tables;

use App\Enums\HandlingType;
use App\Enums\RehabilitationCaseStatus;
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
                    ->description(fn ($record) => $record->client?->category?->name ?? '-'),
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
                    ->label('Petugas Penanggung Jawab')
                    ->searchable(),
                TextColumn::make('received_at')
                    ->label('Tgl Diterima')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('closed_at')
                    ->label('Tgl Ditutup')
                    ->dateTime('d M Y H:i')
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
