<?php

namespace App\Filament\Resources\RehabilitationCases\Schemas;

use App\Enums\HandlingType;
use App\Enums\RehabilitationCaseStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class RehabilitationCaseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kasus Rehabilitasi Sosial')
                    ->components([
                        Grid::make(4)->components([
                            TextEntry::make('case_number')
                                ->label('Nomor Kasus')
                                ->copyable()
                                ->weight('bold')
                                ->icon(Heroicon::OutlinedFolder),
                            TextEntry::make('status')
                                ->label('Tahapan Kasus')
                                ->badge()
                                ->formatStateUsing(fn ($state) => $state instanceof RehabilitationCaseStatus ? $state->label() : (RehabilitationCaseStatus::tryFrom((string) $state)?->label() ?? $state))
                                ->color(fn ($state) => $state instanceof RehabilitationCaseStatus ? $state->color() : (RehabilitationCaseStatus::tryFrom((string) $state)?->color() ?? 'gray')),
                            TextEntry::make('handling_type')
                                ->label('Jenis Penanganan')
                                ->badge()
                                ->formatStateUsing(fn ($state) => $state instanceof HandlingType ? $state->label() : (HandlingType::tryFrom((string) $state)?->label() ?? $state)),
                            TextEntry::make('officer.name')
                                ->label('Pekerja Sosial')
                                ->placeholder('(Belum Ditugaskan)')
                                ->icon(Heroicon::OutlinedUser),
                        ]),
                        Grid::make(3)->components([
                            TextEntry::make('received_at')
                                ->label('Waktu Kasus Diterima')
                                ->dateTime('d F Y, H:i'),
                            TextEntry::make('sourceServiceRequest.request_number')
                                ->label('Sumber Tiket Layanan')
                                ->placeholder('(Bukan dari tiket layanan)')
                                ->badge()
                                ->color('info'),
                            TextEntry::make('sourceComplaint.complaint_number')
                                ->label('Sumber Laporan Pengaduan')
                                ->placeholder('(Bukan dari pengaduan)')
                                ->badge()
                                ->color('warning'),
                        ]),
                    ]),

                Section::make('Data Klien Penerima Manfaat')
                    ->components([
                        Grid::make(3)->components([
                            TextEntry::make('client.name')
                                ->label('Nama Lengkap Klien')
                                ->weight('bold'),
                            TextEntry::make('client.category.name')
                                ->label('Kategori Pemerlu Pelayanan (PPKS)')
                                ->badge()
                                ->color('purple'),
                            TextEntry::make('client.nik')
                                ->label('NIK Klien')
                                ->placeholder('(Tidak Memiliki / Belum Ada)'),
                        ]),
                        Grid::make(3)->components([
                            TextEntry::make('client.gender')
                                ->label('Jenis Kelamin')
                                ->formatStateUsing(fn ($state) => $state === 'male' ? 'Laki-laki' : 'Perempuan'),
                            TextEntry::make('client.birth_date')
                                ->label('Tanggal Lahir')
                                ->date('d F Y')
                                ->placeholder('-'),
                            TextEntry::make('client.village.name')
                                ->label('Desa / Kelurahan'),
                        ]),
                        TextEntry::make('client.address')
                            ->label('Alamat Domisili Klien')
                            ->columnSpanFull(),
                    ]),

                Section::make('Hasil Akhir Pelayanan & Terminasi Kasus')
                    ->components([
                        TextEntry::make('handling_result')
                            ->label('Hasil Penanganan / Terminasi')
                            ->placeholder('(Kasus masih aktif dalam penanganan/monitoring)')
                            ->columnSpanFull(),
                        TextEntry::make('closed_at')
                            ->label('Waktu Penutupan Kasus')
                            ->dateTime('d F Y, H:i')
                            ->placeholder('(Kasus Belum Ditutup)'),
                    ]),
            ]);
    }
}
