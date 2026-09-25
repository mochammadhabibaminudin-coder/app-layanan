<?php

namespace App\Filament\Resources\Referrals\Schemas;

use App\Enums\ReferralStatus;
use App\Models\Assessment;
use App\Models\RehabilitationCase;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReferralForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Surat Rujukan & Kasus Terkait')
                    ->components([
                        Grid::make(3)->components([
                            TextInput::make('referral_number')
                                ->label('Nomor Rujukan')
                                ->placeholder('(Otomatis dibuat sistem)')
                                ->disabled()
                                ->dehydrated(fn ($state) => filled($state)),
                            Select::make('rehabilitation_case_id')
                                ->label('Kasus Rehabilitasi Asal')
                                ->relationship('rehabilitationCase', 'case_number')
                                ->getOptionLabelFromRecordUsing(fn (RehabilitationCase $case) => "{$case->case_number} — {$case->client?->name} ({$case->client?->category?->name})")
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('assessment_id')
                                ->label('Dasar Hasil Assessment')
                                ->relationship('assessment', 'result')
                                ->getOptionLabelFromRecordUsing(fn (Assessment $a) => "Tgl {$a->assessment_date->format('d/m/Y')} — ".substr($a->recommendation, 0, 40).'...')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ]),
                        Grid::make(3)->components([
                            Select::make('referral_institution_id')
                                ->label('Lembaga / Mitra Tujuan Rujukan')
                                ->relationship('institution', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('officer_id')
                                ->label('Petugas Pekerja Sosial')
                                ->options(User::pluck('name', 'id'))
                                ->default(auth()->id())
                                ->required()
                                ->searchable(),
                            DatePicker::make('referral_date')
                                ->label('Tanggal Surat Rujukan')
                                ->default(now())
                                ->required(),
                        ]),
                    ]),

                Section::make('Status & Hasil Pelayanan Rujukan')
                    ->components([
                        Grid::make(2)->components([
                            Select::make('status')
                                ->label('Status Rujukan')
                                ->options(ReferralStatus::class)
                                ->default(ReferralStatus::Draft)
                                ->required(),
                            DateTimePicker::make('completed_at')
                                ->label('Waktu Selesai Penanganan')
                                ->disabled()
                                ->dehydrated(fn ($state) => filled($state)),
                        ]),
                        Textarea::make('service_result')
                            ->label('Hasil Pelayanan dari Lembaga Rujukan')
                            ->placeholder('Catat perkembangan hasil pelayanan dari pihak lembaga/panti/balai/RS')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
