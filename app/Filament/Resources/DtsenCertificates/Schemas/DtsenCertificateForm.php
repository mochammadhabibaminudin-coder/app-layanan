<?php

namespace App\Filament\Resources\DtsenCertificates\Schemas;

use App\Models\DtsenPurpose;
use App\Models\ServiceRequest;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DtsenCertificateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Keterkaitan Tiket Layanan & Tujuan SK')
                    ->components([
                        Grid::make(2)->components([
                            Select::make('service_request_id')
                                ->label('Nomor Tiket Pengajuan')
                                ->relationship('serviceRequest', 'request_number')
                                ->getOptionLabelFromRecordUsing(fn (ServiceRequest $record) => "{$record->request_number} — {$record->applicant_name} ({$record->village?->name})")
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('dtsen_purpose_id')
                                ->label('Tujuan Penggunaan Surat')
                                ->relationship('purpose', 'name')
                                ->getOptionLabelFromRecordUsing(fn (DtsenPurpose $purpose) => "{$purpose->name} (Maks. Desil: {$purpose->max_decile})")
                                ->searchable()
                                ->preload()
                                ->required(),
                        ]),
                        Textarea::make('purpose_description')
                            ->label('Keterangan Keperluan Khusus')
                            ->placeholder('Contoh: Untuk persyaratan pendaftaran SPMB Jalur Afirmasi di SMAN 1 Talun')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('Data Orang yang Diterangkan')
                    ->description('Warga / anak / siswa yang status desilnya diterangkan dalam surat keterangan')
                    ->components([
                        Grid::make(3)->components([
                            TextInput::make('subject_name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('subject_nik')
                                ->label('NIK yang Diterangkan')
                                ->length(16)
                                ->numeric()
                                ->required(),
                            TextInput::make('relationship_to_applicant')
                                ->label('Hubungan dg Pemohon')
                                ->placeholder('Contoh: Anak Kandung, Diri Sendiri')
                                ->required(),
                        ]),
                    ]),

                Section::make('Hasil Verifikasi Data di SIKS-NG')
                    ->description('Pengecekan status kepesertaan DTSEN oleh petugas dinas')
                    ->components([
                        Grid::make(3)->components([
                            Toggle::make('is_registered')
                                ->label('Terdaftar di SIKS-NG / DTSEN')
                                ->inline(false)
                                ->required(),
                            TextInput::make('decile')
                                ->label('Peringkat Desil (1–10)')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(10)
                                ->placeholder('Contoh: 2'),
                            Select::make('checker_id')
                                ->label('Petugas Pemeriksa SIKS-NG')
                                ->options(User::pluck('name', 'id'))
                                ->default(auth()->id())
                                ->searchable(),
                        ]),
                        DateTimePicker::make('checked_at')
                            ->label('Waktu Pengecekan SIKS-NG')
                            ->default(now()),
                    ]),

                Section::make('Penerbitan & Pengesahan Surat Keterangan')
                    ->description('Nomor surat, pejabat penandatangan, dan kode verifikasi QR')
                    ->components([
                        Grid::make(3)->components([
                            TextInput::make('certificate_number')
                                ->label('Nomor Surat Keterangan')
                                ->placeholder('(Terisi otomatis saat disetujui / diterbitkan)')
                                ->disabled()
                                ->dehydrated(fn ($state) => filled($state)),
                            Select::make('signer_id')
                                ->label('Pejabat Penandatangan')
                                ->options(User::pluck('name', 'id'))
                                ->searchable(),
                            TextInput::make('verification_code')
                                ->label('Kode Verifikasi Dokumen (QR)')
                                ->placeholder('(Otomatis dibuat)')
                                ->disabled()
                                ->dehydrated(fn ($state) => filled($state)),
                        ]),
                        Grid::make(2)->components([
                            DateTimePicker::make('issued_at')
                                ->label('Waktu Diterbitkan')
                                ->disabled()
                                ->dehydrated(fn ($state) => filled($state)),
                            DatePicker::make('valid_until')
                                ->label('Masa Berlaku Surat Hingga')
                                ->placeholder('Sesuai ketentuan tujuan penggunaan'),
                        ]),
                    ]),
            ]);
    }
}
