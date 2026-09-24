<?php

namespace App\Filament\Resources\DtsenCertificates\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DtsenCertificateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('service_request_id')
                    ->relationship('serviceRequest', 'id')
                    ->required(),
                TextInput::make('dtsen_purpose_id')
                    ->required()
                    ->numeric(),
                Textarea::make('purpose_description')
                    ->columnSpanFull(),
                TextInput::make('subject_name')
                    ->required(),
                TextInput::make('subject_nik')
                    ->required(),
                TextInput::make('relationship_to_applicant')
                    ->required(),
                Toggle::make('is_registered')
                    ->required(),
                TextInput::make('decile')
                    ->numeric(),
                DateTimePicker::make('checked_at'),
                Select::make('checker_id')
                    ->relationship('checker', 'name'),
                TextInput::make('certificate_number'),
                DateTimePicker::make('issued_at'),
                DatePicker::make('valid_until'),
                Select::make('signer_id')
                    ->relationship('signer', 'name'),
                TextInput::make('file_path'),
                TextInput::make('verification_code')
                    ->required(),
            ]);
    }
}
