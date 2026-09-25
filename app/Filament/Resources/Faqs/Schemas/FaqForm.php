<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tanya Jawab Seputar Layanan (FAQ)')
                    ->components([
                        Select::make('information_page_id')
                            ->label('Halaman Layanan Terkait (Opsional)')
                            ->relationship('informationPage', 'title')
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                        Textarea::make('question')
                            ->label('Pertanyaan yang Sering Diajukan')
                            ->rows(2)
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('answer')
                            ->label('Jawaban Penjelasan')
                            ->rows(4)
                            ->required()
                            ->columnSpanFull(),
                        Grid::make(2)->components([
                            TextInput::make('sort_order')
                                ->label('Nomor Urutan Tampilan')
                                ->numeric()
                                ->default(1)
                                ->required(),
                            Toggle::make('is_active')
                                ->label('Status Aktif')
                                ->default(true)
                                ->inline(false),
                        ]),
                    ]),
            ]);
    }
}
