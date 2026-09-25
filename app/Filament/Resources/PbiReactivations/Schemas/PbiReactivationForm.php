<?php

namespace App\Filament\Resources\PbiReactivations\Schemas;

use App\Enums\MinistryDecision;
use App\Enums\PbiReactivationReason;
use App\Models\ServiceRequest;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PbiReactivationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Peserta & Tiket Pengajuan')
                    ->components([
                        Grid::make(2)->components([
                            Select::make('service_request_id')
                                ->label('Nomor Tiket Layanan')
                                ->relationship('serviceRequest', 'request_number')
                                ->getOptionLabelFromRecordUsing(fn (ServiceRequest $record) => "{$record->request_number} — {$record->applicant_name}")
                                ->searchable()
                                ->preload()
                                ->required(),
                            DatePicker::make('deactivated_date')
                                ->label('Tanggal Nonaktif BPJS')
                                ->placeholder('Pilih tanggal nonaktif'),
                        ]),
                        Grid::make(3)->components([
                            TextInput::make('participant_name')
                                ->label('Nama Peserta BPJS')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('participant_nik')
                                ->label('NIK Peserta')
                                ->length(16)
                                ->numeric()
                                ->required(),
                            TextInput::make('bpjs_card_number')
                                ->label('Nomor Kartu BPJS / KIS')
                                ->required()
                                ->maxLength(30),
                        ]),
                    ]),

                Section::make('Alasan Reaktivasi & Keterangan Medis')
                    ->components([
                        Grid::make(3)->components([
                            Select::make('reason')
                                ->label('Alasan Reaktivasi')
                                ->options(PbiReactivationReason::class)
                                ->required(),
                            TextInput::make('health_facility_name')
                                ->label('Nama Fasilitas Kesehatan')
                                ->placeholder('Contoh: RSUD Ngudi Waluyo'),
                            TextInput::make('health_letter_number')
                                ->label('Nomor Surat Ket. Dokter/Faskes')
                                ->placeholder('Wajib untuk alasan medis/darurat'),
                        ]),
                    ]),

                Section::make('Hasil Verifikasi Kelayakan Dinsos')
                    ->components([
                        Grid::make(2)->components([
                            TextInput::make('decile')
                                ->label('Peringkat Desil Peserta (1–10)')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(10),
                            Textarea::make('eligibility_notes')
                                ->label('Catatan Hasil Verifikasi Kelayakan')
                                ->rows(2),
                        ]),
                    ]),

                Section::make('Surat Rekomendasi Dinas Sosial')
                    ->components([
                        Grid::make(3)->components([
                            TextInput::make('recommendation_number')
                                ->label('Nomor Surat Rekomendasi')
                                ->placeholder('(Otomatis saat diterbitkan)')
                                ->disabled()
                                ->dehydrated(fn ($state) => filled($state)),
                            DateTimePicker::make('recommendation_issued_at')
                                ->label('Waktu Rekomendasi Diterbitkan')
                                ->disabled()
                                ->dehydrated(fn ($state) => filled($state)),
                            Select::make('signer_id')
                                ->label('Pejabat Penandatangan')
                                ->options(User::pluck('name', 'id'))
                                ->searchable(),
                        ]),
                    ]),

                Section::make('Pengusulan SIKS-NG ke Kemensos & Pengaktifan Kembali')
                    ->components([
                        Grid::make(4)->components([
                            DateTimePicker::make('proposed_to_ministry_at')
                                ->label('Waktu Input ke SIKS-NG')
                                ->disabled()
                                ->dehydrated(fn ($state) => filled($state)),
                            Select::make('ministry_decision')
                                ->label('Keputusan Kemensos')
                                ->options(MinistryDecision::class)
                                ->default(MinistryDecision::Pending)
                                ->required(),
                            DateTimePicker::make('ministry_decided_at')
                                ->label('Waktu Putusan Kemensos')
                                ->disabled()
                                ->dehydrated(fn ($state) => filled($state)),
                            DatePicker::make('reactivated_date')
                                ->label('Tanggal Aktif Kembali di BPJS')
                                ->placeholder('Tanggal kartu aktif'),
                        ]),
                    ]),
            ]);
    }
}
