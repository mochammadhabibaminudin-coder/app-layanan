<?php

namespace App\Filament\Resources\DtsenCertificates\Pages;

use App\Filament\Resources\DtsenCertificates\DtsenCertificateResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDtsenCertificate extends EditRecord
{
    protected static string $resource = DtsenCertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
