<?php

namespace App\Filament\Resources\ClientCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClientCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Kategori Pemerlu Pelayanan Kesejahteraan Sosial (PPKS)')
                    ->components([
                        TextInput::make('name')
                            ->label('Nama Kategori PPKS')
                            ->placeholder('Contoh: Lansia Terlantar, Penyandang Disabilitas, Anak Terlantar, ODGJ')
                            ->required()
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                    ]),
            ]);
    }
}
