<?php

namespace App\Filament\Resources\DtsenCertificates\Schemas;

use App\Models\DtsenCertificate;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DtsenCertificateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('serviceRequest.id')
                    ->label('Service request'),
                TextEntry::make('dtsen_purpose_id')
                    ->numeric(),
                TextEntry::make('purpose_description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('subject_name'),
                TextEntry::make('subject_nik'),
                TextEntry::make('relationship_to_applicant'),
                IconEntry::make('is_registered')
                    ->boolean(),
                TextEntry::make('decile')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('checked_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('checker.name')
                    ->label('Checker')
                    ->placeholder('-'),
                TextEntry::make('certificate_number')
                    ->placeholder('-'),
                TextEntry::make('issued_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('valid_until')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('signer.name')
                    ->label('Signer')
                    ->placeholder('-'),
                TextEntry::make('file_path')
                    ->placeholder('-'),
                TextEntry::make('verification_code'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (DtsenCertificate $record): bool => $record->trashed()),
            ]);
    }
}
