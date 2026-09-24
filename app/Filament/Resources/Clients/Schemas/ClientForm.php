<?php

namespace App\Filament\Resources\Clients\Schemas;

use App\Enums\ClientGender;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('client_category_id')
                    ->required()
                    ->numeric(),
                TextInput::make('nik'),
                DatePicker::make('birth_date'),
                Select::make('gender')
                    ->options(ClientGender::class)
                    ->default('male')
                    ->required(),
                Textarea::make('address')
                    ->columnSpanFull(),
                Select::make('village_id')
                    ->relationship('village', 'name'),
                TextInput::make('phone')
                    ->tel(),
            ]);
    }
}
