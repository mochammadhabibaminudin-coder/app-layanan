<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Pengguna')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('No. HP / WA')
                    ->icon(Heroicon::OutlinedPhone)
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('workUnit.name')
                    ->label('Unit Kerja')
                    ->badge()
                    ->color('info')
                    ->placeholder('-'),
                TextColumn::make('district.name')
                    ->label('Kecamatan')
                    ->description(fn ($record) => $record->village?->name ?? '')
                    ->placeholder('(Seluruh Kab. Blitar)'),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('work_unit_id')
                    ->label('Unit Kerja')
                    ->relationship('workUnit', 'name'),
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
