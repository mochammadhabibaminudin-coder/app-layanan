<?php

namespace App\Filament\Resources\ReferralInstitutions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReferralInstitutionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Lembaga / Mitra Rujukan')
                    ->components([
                        Grid::make(3)->components([
                            TextInput::make('name')
                                ->label('Nama Lembaga / Mitra')
                                ->placeholder('Contoh: Balai Rehsos Bina Mandiri')
                                ->required()
                                ->maxLength(255),
                            Select::make('type')
                                ->label('Jenis Lembaga')
                                ->options([
                                    'Panti Sosial' => 'Panti Sosial',
                                    'Balai Rehabilitasi' => 'Balai Rehabilitasi',
                                    'Rumah Sakit / RSJ' => 'Rumah Sakit / RSJ',
                                    'LKS (Lembaga Kesejahteraan Sosial)' => 'LKS (Lembaga Kesejahteraan Sosial)',
                                    'Lembaga Pemasyarakatan' => 'Lembaga Pemasyarakatan',
                                    'Lainnya' => 'Lainnya',
                                ])
                                ->required(),
                            TextInput::make('contact')
                                ->label('Kontak / No. Telepon / PIC')
                                ->placeholder('No. Telp / PIC lembaga'),
                        ]),
                        Textarea::make('address')
                            ->label('Alamat Lengkap Lembaga')
                            ->rows(2)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                    ]),
            ]);
    }
}
