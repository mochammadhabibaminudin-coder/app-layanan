<?php

namespace App\Filament\Resources\PbiReactivations\Schemas;

use App\Enums\MinistryDecision;
use App\Enums\PbiReactivationReason;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PbiReactivationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('service_request_id')
                    ->relationship('serviceRequest', 'id')
                    ->required(),
                TextInput::make('participant_name')
                    ->required(),
                TextInput::make('participant_nik')
                    ->required(),
                TextInput::make('bpjs_card_number')
                    ->required(),
                DatePicker::make('deactivated_date'),
                Select::make('reason')
                    ->options(PbiReactivationReason::class)
                    ->required(),
                TextInput::make('health_facility_name'),
                TextInput::make('health_letter_number'),
                TextInput::make('decile')
                    ->numeric(),
                Textarea::make('eligibility_notes')
                    ->columnSpanFull(),
                TextInput::make('recommendation_number'),
                DateTimePicker::make('recommendation_issued_at'),
                Select::make('signer_id')
                    ->relationship('signer', 'name'),
                DateTimePicker::make('proposed_to_ministry_at'),
                Select::make('ministry_decision')
                    ->options(MinistryDecision::class)
                    ->default('pending')
                    ->required(),
                DateTimePicker::make('ministry_decided_at'),
                DatePicker::make('reactivated_date'),
            ]);
    }
}
