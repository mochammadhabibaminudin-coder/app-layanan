<?php

namespace App\Filament\Resources\ServiceRequests\Schemas;

use App\Enums\ServiceRequestStatus;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ServiceRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ringkasan Tiket Layanan')
                    ->components([
                        Grid::make(4)->components([
                            TextEntry::make('request_number')
                                ->label('Nomor Tiket')
                                ->copyable()
                                ->weight('bold')
                                ->icon(Heroicon::OutlinedTicket),
                            TextEntry::make('serviceType.name')
                                ->label('Jenis Layanan')
                                ->badge()
                                ->color('info'),
                            TextEntry::make('status')
                                ->label('Status Terkini')
                                ->badge()
                                ->formatStateUsing(fn ($state) => $state instanceof ServiceRequestStatus ? $state->label() : (ServiceRequestStatus::tryFrom((string) $state)?->label() ?? $state))
                                ->color(fn ($state) => $state instanceof ServiceRequestStatus ? $state->color() : (ServiceRequestStatus::tryFrom((string) $state)?->color() ?? 'gray')),
                            IconEntry::make('is_priority')
                                ->label('Prioritas / Darurat')
                                ->boolean(),
                        ]),
                        Grid::make(3)->components([
                            TextEntry::make('submitted_at')
                                ->label('Waktu Masuk Tiket')
                                ->dateTime('d F Y, H:i')
                                ->icon(Heroicon::OutlinedCalendar),
                            TextEntry::make('officer.name')
                                ->label('Petugas Pelaksana')
                                ->placeholder('(Belum Ditugaskan)')
                                ->icon(Heroicon::OutlinedUser),
                            TextEntry::make('workUnit.name')
                                ->label('Unit Kerja')
                                ->placeholder('(Belum Ditentukan)')
                                ->icon(Heroicon::OutlinedBuildingOffice),
                        ]),
                    ]),

                Section::make('Data Identitas Pemohon')
                    ->components([
                        Grid::make(3)->components([
                            TextEntry::make('applicant_name')
                                ->label('Nama Lengkap')
                                ->weight('bold'),
                            TextEntry::make('applicant_nik')
                                ->label('NIK')
                                ->copyable(),
                            TextEntry::make('family_card_number')
                                ->label('No. Kartu Keluarga')
                                ->copyable(),
                        ]),
                        Grid::make(3)->components([
                            TextEntry::make('phone')
                                ->label('No. Handphone / WhatsApp')
                                ->copyable()
                                ->icon(Heroicon::OutlinedPhone),
                            TextEntry::make('village.name')
                                ->label('Desa / Kelurahan'),
                            TextEntry::make('village.district.name')
                                ->label('Kecamatan'),
                        ]),
                        TextEntry::make('address')
                            ->label('Alamat Lengkap Domisili')
                            ->columnSpanFull(),
                    ]),

                Section::make('Hasil Pemeriksaan & Catatan Layanan')
                    ->components([
                        Grid::make(2)->components([
                            TextEntry::make('verification_result')
                                ->label('Hasil Verifikasi Dokumen')
                                ->placeholder('(Belum ada catatan verifikasi)'),
                            TextEntry::make('officer_notes')
                                ->label('Catatan Petugas')
                                ->placeholder('(Tidak ada catatan)'),
                        ]),
                        Grid::make(2)->components([
                            TextEntry::make('assessment_notes')
                                ->label('Catatan Assessment')
                                ->placeholder('(Tidak memerlukan assessment)'),
                            TextEntry::make('rejection_reason')
                                ->label('Alasan Penolakan')
                                ->placeholder('(Tidak ditolak)')
                                ->color('danger'),
                        ]),
                        TextEntry::make('service_result')
                            ->label('Hasil Akhir Pelayanan')
                            ->placeholder('(Layanan masih dalam proses)')
                            ->columnSpanFull(),
                        TextEntry::make('completed_at')
                            ->label('Waktu Selesai')
                            ->dateTime('d F Y, H:i')
                            ->placeholder('(Belum selesai)'),
                    ]),
            ]);
    }
}
