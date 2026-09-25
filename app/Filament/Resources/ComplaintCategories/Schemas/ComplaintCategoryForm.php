<?php

namespace App\Filament\Resources\ComplaintCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ComplaintCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Kategori Pengaduan Sosial')
                    ->components([
                        TextInput::make('name')
                            ->label('Nama Kategori Pengaduan')
                            ->placeholder('Contoh: Bantuan Sosial Tidak Tepat Sasaran, ODGJ Terlantar')
                            ->required()
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                    ]),
            ]);
    }
}
