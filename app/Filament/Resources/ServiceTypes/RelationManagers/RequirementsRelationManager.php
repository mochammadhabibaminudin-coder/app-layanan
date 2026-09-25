<?php

namespace App\Filament\Resources\ServiceTypes\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RequirementsRelationManager extends RelationManager
{
    protected static string $relationship = 'requirements';

    protected static ?string $title = 'Dokumen Persyaratan Layanan';

    protected static ?string $modelLabel = 'Persyaratan Dokumen';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Dokumen Persyaratan')
                    ->placeholder('Contoh: KTP Pemohon, Kartu Keluarga, Surat Ket. Faskes')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Grid::make(3)->components([
                    Toggle::make('is_mandatory')
                        ->label('Wajib Diunggah')
                        ->default(true)
                        ->required(),
                    TextInput::make('allowed_mimes')
                        ->label('Format File Diizinkan')
                        ->default('pdf,jpg,png,jpeg')
                        ->required(),
                    TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(1)
                        ->required(),
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('sort_order', 'asc')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('No.')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama Dokumen Persyaratan')
                    ->weight('bold')
                    ->searchable(),
                IconColumn::make('is_mandatory')
                    ->label('Wajib')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('allowed_mimes')
                    ->label('Format Diizinkan')
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Persyaratan Dokumen'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
