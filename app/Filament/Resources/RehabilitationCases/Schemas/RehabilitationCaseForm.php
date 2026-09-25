<?php

namespace App\Filament\Resources\RehabilitationCases\Schemas;

use App\Enums\HandlingType;
use App\Enums\RehabilitationCaseStatus;
use App\Models\Client;
use App\Models\Complaint;
use App\Models\ServiceRequest;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RehabilitationCaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kasus & Identitas Klien')
                    ->components([
                        Grid::make(3)->components([
                            TextInput::make('case_number')
                                ->label('Nomor Kasus')
                                ->placeholder('(Otomatis dibuat sistem)')
                                ->disabled()
                                ->dehydrated(fn ($state) => filled($state)),
                            Select::make('client_id')
                                ->label('Klien Penerima Manfaat')
                                ->relationship('client', 'name')
                                ->getOptionLabelFromRecordUsing(fn (Client $record) => "{$record->name} ({$record->category?->name}) — NIK: ".($record->nik ?? '-'))
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('officer_id')
                                ->label('Petugas Pekerja Sosial')
                                ->options(User::pluck('name', 'id'))
                                ->default(auth()->id())
                                ->searchable(),
                        ]),
                        Grid::make(3)->components([
                            Select::make('handling_type')
                                ->label('Jenis Penanganan')
                                ->options(HandlingType::class)
                                ->default(HandlingType::Direct)
                                ->required(),
                            Select::make('status')
                                ->label('Status Tahapan Kasus')
                                ->options(RehabilitationCaseStatus::class)
                                ->default(RehabilitationCaseStatus::Received)
                                ->required(),
                            DateTimePicker::make('received_at')
                                ->label('Waktu Kasus Diterima')
                                ->default(now())
                                ->required(),
                        ]),
                    ]),

                Section::make('Sumber Asal Kasus')
                    ->description('Hubungkan dengan pengajuan layanan atau laporan pengaduan jika ada')
                    ->components([
                        Grid::make(2)->components([
                            Select::make('service_request_id')
                                ->label('Dari Pengajuan Layanan (Opsional)')
                                ->relationship('sourceServiceRequest', 'request_number')
                                ->getOptionLabelFromRecordUsing(fn (ServiceRequest $req) => "{$req->request_number} — {$req->applicant_name}")
                                ->searchable()
                                ->preload(),
                            Select::make('complaint_id')
                                ->label('Dari Laporan Pengaduan (Opsional)')
                                ->relationship('sourceComplaint', 'complaint_number')
                                ->getOptionLabelFromRecordUsing(fn (Complaint $comp) => "{$comp->complaint_number} — {$comp->reporter_name}")
                                ->searchable()
                                ->preload(),
                        ]),
                    ]),

                Section::make('Hasil Penanganan & Penutupan Kasus')
                    ->description('Wajib dicatat sebelum kasus dinyatakan selesai / ditutup')
                    ->components([
                        Textarea::make('handling_result')
                            ->label('Hasil Akhir Pelayanan & Terminasi Kasus')
                            ->placeholder('Jelaskan hasil pelayanan, kemandirian klien, atau reintegrasi ke keluarga/masyarakat')
                            ->rows(3)
                            ->columnSpanFull(),
                        DateTimePicker::make('closed_at')
                            ->label('Waktu Kasus Ditutup')
                            ->disabled()
                            ->dehydrated(fn ($state) => filled($state)),
                    ]),
            ]);
    }
}
