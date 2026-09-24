<?php

namespace App\Filament\Resources\DtsenCertificates\Pages;

use App\Filament\Resources\DtsenCertificates\DtsenCertificateResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDtsenCertificate extends ViewRecord
{
    protected static string $resource = DtsenCertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
