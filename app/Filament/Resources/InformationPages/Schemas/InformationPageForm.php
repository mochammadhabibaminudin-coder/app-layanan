<?php

namespace App\Filament\Resources\InformationPages\Schemas;

use App\Enums\InformationPageCategory;
use App\Enums\PublishStatus;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class InformationPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Judul & Kategori Informasi')
                    ->components([
                        Grid::make(3)->components([
                            TextInput::make('title')
                                ->label('Judul Konten Layanan / Program')
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                                ->required()
                                ->maxLength(255),
                            TextInput::make('slug')
                                ->label('Slug URL (Permalink)')
                                ->required()
                                ->maxLength(255),
                            Select::make('category')
                                ->label('Kategori')
                                ->options(InformationPageCategory::class)
                                ->default(InformationPageCategory::Program)
                                ->required(),
                        ]),
                        Grid::make(2)->components([
                            Select::make('service_type_id')
                                ->label('Jenis Layanan Terkait (Opsional)')
                                ->relationship('serviceType', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('manager_id')
                                ->label('Pengelola Konten')
                                ->options(User::pluck('name', 'id'))
                                ->default(auth()->id())
                                ->searchable(),
                        ]),
                    ]),

                Section::make('Deskripsi, Persyaratan & Alur Prosedur')
                    ->components([
                        Textarea::make('description')
                            ->label('Ringkasan / Deskripsi Layanan')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('requirements')
                            ->label('Persyaratan Dokumen & Ketentuan')
                            ->rows(4)
                            ->columnSpanFull(),
                        Textarea::make('procedure')
                            ->label('Alur / Prosedur Pelayanan')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Informasi Operasional & Publikasi')
                    ->components([
                        Grid::make(3)->components([
                            TextInput::make('service_hours')
                                ->label('Jam Pelayanan')
                                ->placeholder('Senin - Kamis: 08.00 - 15.00 WIB'),
                            TextInput::make('location')
                                ->label('Lokasi Kantor / Loket')
                                ->placeholder('Kantor Dinas Sosial Kab. Blitar'),
                            TextInput::make('contact')
                                ->label('Kontak / Call Center')
                                ->placeholder('0342-801xxx / WhatsApp Center'),
                        ]),
                        Grid::make(2)->components([
                            Select::make('publish_status')
                                ->label('Status Publikasi')
                                ->options(PublishStatus::class)
                                ->default(PublishStatus::Published)
                                ->required(),
                            DateTimePicker::make('published_at')
                                ->label('Waktu Dipublikasikan')
                                ->default(now()),
                        ]),
                    ]),
            ]);
    }
}
