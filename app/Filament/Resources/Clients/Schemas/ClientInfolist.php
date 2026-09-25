<?php

namespace App\Filament\Resources\Clients\Schemas;

use App\Enums\ClientGender;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ClientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Klien Pemerlu Pelayanan (PPKS)')
                    ->components([
                        Grid::make(3)->components([
                            TextEntry::make('name')
                                ->label('Nama Lengkap')
                                ->weight('bold')
                                ->icon(Heroicon::OutlinedUser),
                            TextEntry::make('category.name')
                                ->label('Kategori PPKS')
                                ->badge()
                                ->color('purple'),
                            TextEntry::make('nik')
                                ->label('NIK Klien')
                                ->copyable()
                                ->placeholder('(Tanpa NIK)'),
                        ]),
                        Grid::make(3)->components([
                            TextEntry::make('gender')
                                ->label('Jenis Kelamin')
                                ->badge()
                                ->formatStateUsing(fn ($state) => $state instanceof ClientGender ? $state->label() : ($state === 'male' ? 'Laki-laki' : 'Perempuan'))
                                ->color(fn ($state) => ($state === 'male' || $state === ClientGender::Male) ? 'info' : 'pink'),
                            TextEntry::make('birth_date')
                                ->label('Tanggal Lahir')
                                ->date('d F Y')
                                ->placeholder('-'),
                            TextEntry::make('phone')
                                ->label('Kontak / No. Telepon')
                                ->placeholder('-'),
                        ]),
                    ]),

                Section::make('Domisili & Alamat Klien')
                    ->components([
                        Grid::make(2)->components([
                            TextEntry::make('village.name')
                                ->label('Desa / Kelurahan')
                                ->placeholder('-'),
                            TextEntry::make('village.district.name')
                                ->label('Kecamatan')
                                ->placeholder('-'),
                        ]),
                        TextEntry::make('address')
                            ->label('Alamat Lengkap')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
