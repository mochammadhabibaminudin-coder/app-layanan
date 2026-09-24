<?php

namespace App\Filament\Resources\RehabilitationCases\Schemas;

use App\Enums\HandlingType;
use App\Enums\RehabilitationCaseStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RehabilitationCaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('case_number')
                    ->required(),
                Select::make('client_id')
                    ->relationship('client', 'name')
                    ->required(),
                TextInput::make('service_request_id')
                    ->numeric(),
                TextInput::make('complaint_id')
                    ->numeric(),
                Select::make('officer_id')
                    ->relationship('officer', 'name'),
                Select::make('handling_type')
                    ->options(HandlingType::class)
                    ->default('direct')
                    ->required(),
                Select::make('status')
                    ->options(RehabilitationCaseStatus::class)
                    ->default('received')
                    ->required(),
                Textarea::make('handling_result')
                    ->columnSpanFull(),
                DateTimePicker::make('received_at')
                    ->required(),
                DateTimePicker::make('closed_at'),
            ]);
    }
}
