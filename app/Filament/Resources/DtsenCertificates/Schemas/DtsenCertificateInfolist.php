<?php

namespace App\Filament\Resources\DtsenCertificates\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class DtsenCertificateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dokumen Surat Keterangan')
                    ->components([
                        Grid::make(3)->components([
                            TextEntry::make('certificate_number')
                                ->label('Nomor Surat Keterangan')
                                ->placeholder('(Belum Terbit)')
                                ->copyable()
                                ->weight('bold')
                                ->icon(Heroicon::OutlinedDocumentCheck),
                            TextEntry::make('serviceRequest.request_number')
                                ->label('Nomor Tiket Layanan')
                                ->copyable()
                                ->icon(Heroicon::OutlinedTicket),
                            TextEntry::make('purpose.name')
                                ->label('Tujuan Penggunaan')
                                ->badge()
                                ->color('info'),
                        ]),
                        Grid::make(3)->components([
                            TextEntry::make('issued_at')
                                ->label('Tanggal Diterbitkan')
                                ->dateTime('d F Y, H:i')
                                ->placeholder('(Belum Diterbitkan)'),
                            TextEntry::make('valid_until')
                                ->label('Berlaku Hingga')
                                ->date('d F Y')
                                ->placeholder('(Tidak Dibatasi / Berlaku Selamanya)'),
                            TextEntry::make('signer.name')
                                ->label('Pejabat Penandatangan')
                                ->placeholder('(Belum Ditandatangani)'),
                        ]),
                        TextEntry::make('purpose_description')
                            ->label('Keterangan Keperluan')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),

                Section::make('Data Warga yang Diterangkan')
                    ->components([
                        Grid::make(3)->components([
                            TextEntry::make('subject_name')
                                ->label('Nama Lengkap')
                                ->weight('bold'),
                            TextEntry::make('subject_nik')
                                ->label('NIK')
                                ->copyable(),
                            TextEntry::make('relationship_to_applicant')
                                ->label('Hubungan dengan Pemohon'),
                        ]),
                    ]),

                Section::make('Hasil Verifikasi Data SIKS-NG')
                    ->components([
                        Grid::make(4)->components([
                            IconEntry::make('is_registered')
                                ->label('Terdaftar di SIKS-NG')
                                ->boolean(),
                            TextEntry::make('decile')
                                ->label('Peringkat Desil')
                                ->badge()
                                ->formatStateUsing(fn ($state) => $state ? "Desil {$state}" : '(Belum Dicek)')
                                ->color(fn ($state) => match (true) {
                                    $state <= 2 => 'success',
                                    $state <= 4 => 'info',
                                    $state <= 5 => 'warning',
                                    default => 'danger',
                                }),
                            TextEntry::make('checked_at')
                                ->label('Waktu Cek')
                                ->dateTime('d M Y H:i')
                                ->placeholder('-'),
                            TextEntry::make('checker.name')
                                ->label('Petugas Pemeriksa')
                                ->placeholder('-'),
                        ]),
                        TextEntry::make('verification_code')
                            ->label('Kode Verifikasi QR (Cek Keaslian)')
                            ->copyable()
                            ->icon(Heroicon::OutlinedQrCode)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
