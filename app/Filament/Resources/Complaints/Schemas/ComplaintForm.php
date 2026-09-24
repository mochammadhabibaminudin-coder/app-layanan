<?php

namespace App\Filament\Resources\Complaints\Schemas;

use App\Enums\ComplaintStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ComplaintForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('complaint_number')
                    ->required(),
                TextInput::make('complaint_category_id')
                    ->required()
                    ->numeric(),
                Select::make('reporter_id')
                    ->relationship('reporter', 'name'),
                TextInput::make('reporter_name')
                    ->required(),
                TextInput::make('reporter_phone')
                    ->tel()
                    ->required(),
                Textarea::make('location_detail')
                    ->required()
                    ->columnSpanFull(),
                Select::make('village_id')
                    ->relationship('village', 'name')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                DateTimePicker::make('reported_at')
                    ->required(),
                Select::make('officer_id')
                    ->relationship('officer', 'name'),
                Select::make('status')
                    ->options(ComplaintStatus::class)
                    ->default('received')
                    ->required(),
                Textarea::make('verification_result')
                    ->columnSpanFull(),
                Textarea::make('action_taken')
                    ->columnSpanFull(),
                Select::make('duplicate_of_id')
                    ->relationship('duplicateOf', 'id'),
                DateTimePicker::make('resolved_at'),
            ]);
    }
}
