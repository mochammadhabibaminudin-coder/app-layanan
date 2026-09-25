<?php

namespace App\Filament\Resources\Complaints\Schemas;

use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ComplaintForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Laporan Pengaduan')
                    ->components([
                        Grid::make(4)->components([
                            TextInput::make('complaint_number')
                                ->label('Nomor Laporan')
                                ->placeholder('(Otomatis dibuat sistem)')
                                ->disabled()
                                ->dehydrated(fn ($state) => filled($state)),
                            Select::make('complaint_category_id')
                                ->label('Kategori Masalah Sosial')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('status')
                                ->label('Status Laporan')
                                ->options(ComplaintStatus::class)
                                ->default(ComplaintStatus::Received)
                                ->required(),
                            DateTimePicker::make('reported_at')
                                ->label('Waktu Laporan')
                                ->default(now())
                                ->required(),
                        ]),
                    ]),

                Section::make('Identitas Pelapor')
                    ->components([
                        Grid::make(3)->components([
                            TextInput::make('reporter_name')
                                ->label('Nama Lengkap Pelapor')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('reporter_phone')
                                ->label('Nomor HP / WhatsApp Pelapor')
                                ->tel()
                                ->required(),
                            Select::make('reporter_id')
                                ->label('Akun Pengguna Terdaftar (Opsional)')
                                ->relationship('reporter', 'name')
                                ->searchable()
                                ->preload(),
                        ]),
                    ]),

                Section::make('Lokasi Kejadian & Uraian Masalah')
                    ->components([
                        Grid::make(2)->components([
                            Select::make('village_id')
                                ->label('Desa / Kelurahan Kejadian')
                                ->relationship('village', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('location_detail')
                                ->label('Detail Alamat / Lokasi Kejadian')
                                ->placeholder('Contoh: Depan Pasar Kanigoro, RT 02 RW 01')
                                ->required(),
                        ]),
                        Textarea::make('description')
                            ->label('Uraian Lengkap Permasalahan Sosial')
                            ->placeholder('Jelaskan secara rinci permasalahan, kondisi korban/warga, dan kebutuhan penanganan mendesak')
                            ->rows(4)
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Penanganan & Verifikasi Petugas')
                    ->components([
                        Grid::make(2)->components([
                            Select::make('officer_id')
                                ->label('Petugas yang Ditugaskan')
                                ->options(User::pluck('name', 'id'))
                                ->default(auth()->id())
                                ->searchable(),
                            Select::make('duplicate_of_id')
                                ->label('Duplikat dari Laporan Lain (Jika Ada)')
                                ->relationship('duplicateOf', 'complaint_number')
                                ->getOptionLabelFromRecordUsing(fn (Complaint $comp) => "{$comp->complaint_number} — {$comp->reporter_name} ({$comp->village?->name})")
                                ->searchable()
                                ->preload(),
                        ]),
                        Grid::make(2)->components([
                            Textarea::make('verification_result')
                                ->label('Hasil Verifikasi Awal Lapangan')
                                ->rows(3),
                            Textarea::make('action_taken')
                                ->label('Tindakan / Hasil Penanganan')
                                ->helperText('Wajib diisi sebelum pengaduan dinyatakan selesai (Resolved)')
                                ->rows(3),
                        ]),
                        DateTimePicker::make('resolved_at')
                            ->label('Waktu Selesai Ditangani')
                            ->disabled()
                            ->dehydrated(fn ($state) => filled($state)),
                    ]),
            ]);
    }
}
