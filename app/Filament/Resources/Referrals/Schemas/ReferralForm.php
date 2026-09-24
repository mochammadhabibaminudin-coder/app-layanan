<?php

namespace App\Filament\Resources\Referrals\Schemas;

use App\Enums\ReferralStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReferralForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('referral_number')
                    ->required(),
                Select::make('rehabilitation_case_id')
                    ->relationship('rehabilitationCase', 'id')
                    ->required(),
                Select::make('assessment_id')
                    ->relationship('assessment', 'id')
                    ->required(),
                TextInput::make('referral_institution_id')
                    ->required()
                    ->numeric(),
                Select::make('officer_id')
                    ->relationship('officer', 'name')
                    ->required(),
                DatePicker::make('referral_date')
                    ->required(),
                Select::make('status')
                    ->options(ReferralStatus::class)
                    ->default('draft')
                    ->required(),
                Textarea::make('service_result')
                    ->columnSpanFull(),
                DateTimePicker::make('completed_at'),
            ]);
    }
}
