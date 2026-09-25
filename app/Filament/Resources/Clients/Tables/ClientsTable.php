<?php

namespace App\Filament\Resources\Clients\Tables;

use App\Enums\ClientGender;
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

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Klien')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('category.name')
                    ->label('Kategori PPKS')
                    ->badge()
                    ->color('purple')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->placeholder('(Tanpa NIK)'),
                TextColumn::make('gender')
                    ->label('Gender')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof ClientGender ? $state->label() : ($state === 'male' ? 'Laki-laki' : 'Perempuan'))
                    ->color(fn ($state) => ($state === 'male' || $state === ClientGender::Male) ? 'info' : 'pink'),
                TextColumn::make('village.name')
                    ->label('Desa / Kelurahan')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('phone')
                    ->label('Kontak')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('cases_count')
                    ->counts('cases')
                    ->label('Jumlah Kasus')
                    ->badge()
                    ->color('primary'),
            ])
            ->filters([
                SelectFilter::make('client_category_id')
                    ->label('Kategori PPKS')
                    ->relationship('category', 'name'),
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
