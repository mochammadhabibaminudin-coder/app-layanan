<?php

namespace App\Filament\Resources\DtsenPurposes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DtsenPurposesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Tujuan Penggunaan')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('max_decile')
                    ->label('Batas Maksimal Desil')
                    ->badge()
                    ->formatStateUsing(fn ($state) => "Maks. Desil {$state}")
                    ->color('warning')
                    ->sortable(),
                TextColumn::make('validity_days')
                    ->label('Masa Berlaku')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} Hari" : 'Selamanya')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
