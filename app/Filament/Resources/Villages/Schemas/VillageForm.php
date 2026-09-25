<?php

namespace App\Filament\Resources\Villages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VillageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Desa / Kelurahan')
                    ->components([
                        Grid::make(3)->components([
                            Select::make('district_id')
                                ->label('Kecamatan')
                                ->relationship('district', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('code')
                                ->label('Kode Desa / Kelurahan')
                                ->placeholder('Contoh: 35.05.01.2001')
                                ->required(),
                            TextInput::make('name')
                                ->label('Nama Desa / Kelurahan')
                                ->required(),
                        ]),
                    ]),
            ]);
    }
}
