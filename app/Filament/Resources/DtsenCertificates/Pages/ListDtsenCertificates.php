<?php

namespace App\Filament\Resources\DtsenCertificates\Pages;

use App\Filament\Resources\DtsenCertificates\DtsenCertificateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDtsenCertificates extends ListRecords
{
    protected static string $resource = DtsenCertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
