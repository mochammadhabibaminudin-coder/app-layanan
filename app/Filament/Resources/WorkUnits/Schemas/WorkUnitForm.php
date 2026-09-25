<?php

namespace App\Filament\Resources\WorkUnits\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WorkUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Unit Kerja / Bidang Dinas Sosial')
                    ->components([
                        TextInput::make('name')
                            ->label('Nama Unit Kerja')
                            ->placeholder('Contoh: Bidang Perlindungan dan Jaminan Sosial')
                            ->required()
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                    ]),
            ]);
    }
}
