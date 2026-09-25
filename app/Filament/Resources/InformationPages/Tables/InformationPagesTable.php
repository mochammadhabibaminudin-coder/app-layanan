<?php

namespace App\Filament\Resources\InformationPages\Tables;

use App\Enums\InformationPageCategory;
use App\Enums\PublishStatus;
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

class InformationPagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Layanan / Program')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof InformationPageCategory ? $state->label() : (InformationPageCategory::tryFrom((string) $state)?->label() ?? $state))
                    ->color('info')
                    ->searchable(),
                TextColumn::make('publish_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof PublishStatus ? $state->label() : (PublishStatus::tryFrom((string) $state)?->label() ?? $state))
                    ->color(fn ($state) => match ($state instanceof PublishStatus ? $state->value : $state) {
                        'published' => 'success',
                        'archived' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('published_at')
                    ->label('Tgl Publikasi')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('serviceType.name')
                    ->label('Layanan Terkait')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('manager.name')
                    ->label('Pengelola')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(collect(InformationPageCategory::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])),
                SelectFilter::make('publish_status')
                    ->label('Status Publikasi')
                    ->options(collect(PublishStatus::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])),
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
