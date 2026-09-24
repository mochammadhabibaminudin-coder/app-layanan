<?php

namespace App\Filament\Resources\ServiceRequests\Schemas;

use App\Enums\ServiceRequestStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServiceRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('request_number')
                    ->required(),
                Select::make('service_type_id')
                    ->relationship('serviceType', 'name')
                    ->required(),
                Select::make('submitter_id')
                    ->relationship('submitter', 'name'),
                TextInput::make('applicant_name')
                    ->required(),
                TextInput::make('applicant_nik')
                    ->required(),
                TextInput::make('family_card_number')
                    ->required(),
                Textarea::make('address')
                    ->required()
                    ->columnSpanFull(),
                Select::make('village_id')
                    ->relationship('village', 'name')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                DateTimePicker::make('submitted_at')
                    ->required(),
                Select::make('officer_id')
                    ->relationship('officer', 'name'),
                Select::make('work_unit_id')
                    ->relationship('workUnit', 'name'),
                Select::make('status')
                    ->options(ServiceRequestStatus::class)
                    ->default('submitted')
                    ->required(),
                Toggle::make('is_priority')
                    ->required(),
                Textarea::make('verification_result')
                    ->columnSpanFull(),
                Textarea::make('officer_notes')
                    ->columnSpanFull(),
                Textarea::make('assessment_notes')
                    ->columnSpanFull(),
                Textarea::make('service_result')
                    ->columnSpanFull(),
                Textarea::make('rejection_reason')
                    ->columnSpanFull(),
                DateTimePicker::make('completed_at'),
            ]);
    }
}
