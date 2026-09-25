<?php

namespace App\Filament\Resources\ServiceTypes\Schemas;

use App\Enums\ServiceHandler;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Jenis Layanan Sosial')
                    ->components([
                        Grid::make(3)->components([
                            TextInput::make('code')
                                ->label('Kode Layanan')
                                ->placeholder('Contoh: DTSEN, PBI, REHSOS')
                                ->required()
                                ->maxLength(20),
                            TextInput::make('name')
                                ->label('Nama Layanan')
                                ->placeholder('Nama lengkap jenis layanan sosial')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('category')
                                ->label('Kategori Bidang')
                                ->placeholder('Contoh: Linjamsos, Rehsos')
                                ->maxLength(100),
                        ]),
                        Textarea::make('description')
                            ->label('Deskripsi Layanan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Konfigurasi Pemrosesan & SLA')
                    ->components([
                        Grid::make(4)->components([
                            Select::make('handler')
                                ->label('Tipe Alur Handler')
                                ->options(ServiceHandler::class)
                                ->default(ServiceHandler::Generic)
                                ->required(),
                            TextInput::make('sla_days')
                                ->label('Standar Waktu SLA (Hari)')
                                ->numeric()
                                ->minValue(1)
                                ->placeholder('Contoh: 3'),
                            Toggle::make('needs_assessment')
                                ->label('Perlu Assessment Khusus')
                                ->helperText('Aktifkan jika memerlukan form assessment lapangan')
                                ->inline(false),
                            Toggle::make('is_active')
                                ->label('Status Layanan Aktif')
                                ->default(true)
                                ->inline(false),
                        ]),
                    ]),
            ]);
    }
}
