<?php

namespace App\Filament\Resources\Clients\Schemas;

use App\Enums\ClientGender;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Klien Pemerlu Pelayanan (PPKS)')
                    ->components([
                        Grid::make(3)->components([
                            TextInput::make('name')
                                ->label('Nama Lengkap Klien')
                                ->required()
                                ->maxLength(255),
                            Select::make('client_category_id')
                                ->label('Kategori PPKS')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('nik')
                                ->label('NIK Klien (Jika Ada)')
                                ->length(16)
                                ->numeric()
                                ->placeholder('Boleh kosong jika terlantar/tanpa identitas'),
                        ]),
                        Grid::make(3)->components([
                            Select::make('gender')
                                ->label('Jenis Kelamin')
                                ->options(ClientGender::class)
                                ->default(ClientGender::Male)
                                ->required(),
                            DatePicker::make('birth_date')
                                ->label('Tanggal Lahir')
                                ->placeholder('Pilih tanggal lahir'),
                            TextInput::make('phone')
                                ->label('No. Telepon / Kontak Keluarga')
                                ->tel(),
                        ]),
                    ]),

                Section::make('Domisili & Alamat Tempat Tinggal')
                    ->components([
                        Grid::make(2)->components([
                            Select::make('village_id')
                                ->label('Desa / Kelurahan Domisili')
                                ->relationship('village', 'name')
                                ->searchable()
                                ->preload(),
                            TextInput::make('address')
                                ->label('Detail Alamat (Dusun/RT/RW)')
                                ->placeholder('Alamat lengkap domisili klien'),
                        ]),
                    ]),
            ]);
    }
}
