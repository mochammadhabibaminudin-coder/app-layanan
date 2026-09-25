<?php

namespace App\Filament\Resources\ServiceRequests\Schemas;

use App\Enums\ServiceRequestStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengajuan Layanan')
                    ->description('Detail jenis layanan dan tiket')
                    ->components([
                        Grid::make(3)->components([
                            TextInput::make('request_number')
                                ->label('Nomor Tiket')
                                ->placeholder('(Otomatis dibuat sistem)')
                                ->disabled()
                                ->dehydrated(fn ($state) => filled($state)),
                            Select::make('service_type_id')
                                ->label('Jenis Layanan')
                                ->relationship('serviceType', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Toggle::make('is_priority')
                                ->label('Prioritas / Darurat Medis')
                                ->helperText('Pengajuan darurat akan diprioritaskan di antrean')
                                ->inline(false),
                        ]),
                    ]),

                Section::make('Identitas Pemohon')
                    ->description('Data kependudukan warga pemohon layanan')
                    ->components([
                        Grid::make(3)->components([
                            TextInput::make('applicant_name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('applicant_nik')
                                ->label('NIK Pemohon')
                                ->length(16)
                                ->numeric()
                                ->required(),
                            TextInput::make('family_card_number')
                                ->label('Nomor Kartu Keluarga')
                                ->length(16)
                                ->numeric()
                                ->required(),
                        ]),
                        Grid::make(2)->components([
                            TextInput::make('phone')
                                ->label('Nomor HP / WhatsApp')
                                ->tel()
                                ->required(),
                            Select::make('village_id')
                                ->label('Desa / Kelurahan Domisili')
                                ->relationship('village', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ]),
                        Textarea::make('address')
                            ->label('Alamat Lengkap (RT/RW/Dusun)')
                            ->rows(2)
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Penugasan & Status Pemrosesan')
                    ->description('Unit kerja penanggung jawab dan status tahapan')
                    ->components([
                        Grid::make(3)->components([
                            Select::make('work_unit_id')
                                ->label('Unit Kerja')
                                ->relationship('workUnit', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('officer_id')
                                ->label('Petugas Penanggung Jawab')
                                ->relationship('officer', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('status')
                                ->label('Status Layanan')
                                ->options(ServiceRequestStatus::class)
                                ->default(ServiceRequestStatus::Submitted)
                                ->required(),
                        ]),
                    ]),

                Section::make('Catatan Pemeriksaan & Hasil Layanan')
                    ->description('Hasil verifikasi, catatan assessment, dan keputusan akhir')
                    ->components([
                        Grid::make(2)->components([
                            Textarea::make('verification_result')
                                ->label('Hasil Verifikasi Dokumen & Data')
                                ->rows(3),
                            Textarea::make('officer_notes')
                                ->label('Catatan Petugas')
                                ->rows(3),
                        ]),
                        Grid::make(2)->components([
                            Textarea::make('assessment_notes')
                                ->label('Catatan Assessment (Jika Ada)')
                                ->rows(3),
                            Textarea::make('rejection_reason')
                                ->label('Alasan Penolakan (Bila Ditolak)')
                                ->rows(3),
                        ]),
                        Textarea::make('service_result')
                            ->label('Hasil Akhir Pelayanan')
                            ->helperText('Wajib diisi sebelum layanan dinyatakan selesai (Completed)')
                            ->rows(3)
                            ->columnSpanFull(),
                        DateTimePicker::make('completed_at')
                            ->label('Waktu Selesai Pelayanan')
                            ->disabled()
                            ->dehydrated(fn ($state) => filled($state)),
                    ]),
            ]);
    }
}
