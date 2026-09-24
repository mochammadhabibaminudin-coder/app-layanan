<?php

namespace App\Filament\Resources\Referrals\Schemas;

use App\Models\Referral;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ReferralInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('referral_number'),
                TextEntry::make('rehabilitationCase.id')
                    ->label('Rehabilitation case'),
                TextEntry::make('assessment.id')
                    ->label('Assessment'),
                TextEntry::make('referral_institution_id')
                    ->numeric(),
                TextEntry::make('officer.name')
                    ->label('Officer'),
                TextEntry::make('referral_date')
                    ->date(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('service_result')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('completed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Referral $record): bool => $record->trashed()),
            ]);
    }
}
