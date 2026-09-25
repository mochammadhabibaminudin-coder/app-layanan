<?php

namespace App\Filament\Resources\ReferralInstitutions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReferralInstitutionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Lembaga / Mitra')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Jenis Lembaga')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('contact')
                    ->label('Kontak / PIC')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('address')
                    ->label('Alamat')
                    ->limit(50)
                    ->placeholder('-'),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Jenis Lembaga')
                    ->options([
                        'Panti Sosial' => 'Panti Sosial',
                        'Balai Rehabilitasi' => 'Balai Rehabilitasi',
                        'Rumah Sakit / RSJ' => 'Rumah Sakit / RSJ',
                        'LKS (Lembaga Kesejahteraan Sosial)' => 'LKS',
                    ]),
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
