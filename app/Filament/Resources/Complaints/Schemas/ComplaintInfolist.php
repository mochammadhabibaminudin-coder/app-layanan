<?php

namespace App\Filament\Resources\Complaints\Schemas;

use App\Enums\ComplaintStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ComplaintInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Laporan Pengaduan')
                    ->components([
                        Grid::make(4)->components([
                            TextEntry::make('complaint_number')
                                ->label('Nomor Laporan')
                                ->copyable()
                                ->weight('bold')
                                ->icon(Heroicon::OutlinedExclamationTriangle),
                            TextEntry::make('category.name')
                                ->label('Kategori Masalah')
                                ->badge()
                                ->color('info'),
                            TextEntry::make('status')
                                ->label('Status Laporan')
                                ->badge()
                                ->formatStateUsing(fn ($state) => $state instanceof ComplaintStatus ? $state->label() : (ComplaintStatus::tryFrom((string) $state)?->label() ?? $state))
                                ->color(fn ($state) => $state instanceof ComplaintStatus ? $state->color() : (ComplaintStatus::tryFrom((string) $state)?->color() ?? 'gray')),
                            TextEntry::make('reported_at')
                                ->label('Waktu Laporan Masuk')
                                ->dateTime('d F Y, H:i')
                                ->icon(Heroicon::OutlinedClock),
                        ]),
                        Grid::make(3)->components([
                            TextEntry::make('reporter_name')
                                ->label('Nama Pelapor')
                                ->weight('bold'),
                            TextEntry::make('reporter_phone')
                                ->label('Kontak / WA Pelapor')
                                ->copyable()
                                ->icon(Heroicon::OutlinedPhone),
                            TextEntry::make('officer.name')
                                ->label('Petugas yang Ditugaskan')
                                ->placeholder('(Belum Ditugaskan)')
                                ->icon(Heroicon::OutlinedUser),
                        ]),
                    ]),

                Section::make('Lokasi & Deskripsi Permasalahan')
                    ->components([
                        Grid::make(2)->components([
                            TextEntry::make('village.name')
                                ->label('Desa / Kelurahan Kejadian'),
                            TextEntry::make('village.district.name')
                                ->label('Kecamatan'),
                        ]),
                        TextEntry::make('location_detail')
                            ->label('Detail Alamat Kejadian')
                            ->columnSpanFull(),
                        TextEntry::make('description')
                            ->label('Uraian Permasalahan Sosial')
                            ->columnSpanFull(),
                    ]),

                Section::make('Hasil Pemeriksaan & Penanganan Lapangan')
                    ->components([
                        Grid::make(2)->components([
                            TextEntry::make('verification_result')
                                ->label('Hasil Verifikasi Awal')
                                ->placeholder('(Belum Ada Catatan Verifikasi)'),
                            TextEntry::make('action_taken')
                                ->label('Tindakan & Hasil Akhir')
                                ->placeholder('(Belum Selesai Ditangani)'),
                        ]),
                        Grid::make(2)->components([
                            TextEntry::make('duplicateOf.complaint_number')
                                ->label('Duplikat dari Laporan')
                                ->placeholder('(Bukan Laporan Duplikat)')
                                ->badge()
                                ->color('gray'),
                            TextEntry::make('resolved_at')
                                ->label('Waktu Selesai Ditangani')
                                ->dateTime('d F Y, H:i')
                                ->placeholder('(Belum Selesai)'),
                        ]),
                    ]),
            ]);
    }
}
