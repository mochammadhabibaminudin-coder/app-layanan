<?php

namespace App\Filament\Resources\DtsenPurposes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DtsenPurposeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tujuan Penggunaan SK DTSEN')
                    ->description('Atur peruntukan surat keterangan dan ambang batas desil yang diizinkan')
                    ->components([
                        Grid::make(2)->components([
                            TextInput::make('code')
                                ->label('Kode Tujuan')
                                ->placeholder('Contoh: spmb, pip, kip_kuliah, bansos')
                                ->required(),
                            TextInput::make('name')
                                ->label('Nama Tujuan Penggunaan')
                                ->placeholder('Contoh: SPMB Jalur Afirmasi / KIP Kuliah')
                                ->required(),
                        ]),
                        Grid::make(3)->components([
                            TextInput::make('max_decile')
                                ->label('Batas Maksimal Desil')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(10)
                                ->default(5)
                                ->helperText('Surat hanya dapat diterbitkan bila desil pemohon <= batas ini')
                                ->required(),
                            TextInput::make('validity_days')
                                ->label('Masa Berlaku (Hari)')
                                ->numeric()
                                ->placeholder('Kosongkan jika berlaku selamanya')
                                ->helperText('Contoh: 90 hari / 180 hari'),
                            Toggle::make('is_active')
                                ->label('Status Aktif')
                                ->default(true)
                                ->inline(false),
                        ]),
                    ]),
            ]);
    }
}
