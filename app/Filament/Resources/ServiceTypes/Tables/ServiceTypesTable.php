<?php

namespace App\Filament\Resources\ServiceTypes\Tables;

use App\Enums\ServiceHandler;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ServiceTypesTable
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
                    ->label('Nama Layanan Sosial')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Bidang')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                TextColumn::make('handler')
                    ->label('Alur Handler')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof ServiceHandler ? $state->label() : (ServiceHandler::tryFrom((string) $state)?->label() ?? $state))
                    ->color('purple'),
                TextColumn::make('requirements_count')
                    ->counts('requirements')
                    ->label('Jml Dokumen Syarat')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('sla_days')
                    ->label('SLA')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} Hari" : '-')
                    ->sortable(),
                IconColumn::make('needs_assessment')
                    ->label('Assessment')
                    ->boolean()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('handler')
                    ->label('Alur Handler')
                    ->options(collect(ServiceHandler::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])),
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
