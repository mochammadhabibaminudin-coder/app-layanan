<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Akun Pengguna')
                    ->components([
                        Grid::make(3)->components([
                            TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label('Alamat Email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                            TextInput::make('password')
                                ->label('Password')
                                ->password()
                                ->dehydrated(fn ($state) => filled($state))
                                ->required(fn ($livewire) => $livewire instanceof CreateRecord)
                                ->helperText('Kosongkan jika tidak ingin mengubah password'),
                        ]),
                        Grid::make(3)->components([
                            TextInput::make('phone')
                                ->label('No. Handphone / WA')
                                ->tel(),
                            TextInput::make('nik')
                                ->label('NIK Petugas')
                                ->length(16)
                                ->numeric(),
                            Toggle::make('is_active')
                                ->label('Status Akun Aktif')
                                ->default(true)
                                ->inline(false),
                        ]),
                    ]),

                Section::make('Penugasan Dinas & Wilayah Operasional')
                    ->description('Tentukan unit kerja atau wilayah tugas operator')
                    ->components([
                        Grid::make(3)->components([
                            Select::make('work_unit_id')
                                ->label('Unit Kerja / Bidang')
                                ->relationship('workUnit', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('district_id')
                                ->label('Kecamatan Wilayah Tugas')
                                ->relationship('district', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('village_id')
                                ->label('Desa / Kelurahan Wilayah Tugas')
                                ->relationship('village', 'name')
                                ->searchable()
                                ->preload(),
                        ]),
                    ]),
            ]);
    }
}
