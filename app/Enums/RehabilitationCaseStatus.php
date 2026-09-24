<?php

namespace App\Enums;

enum RehabilitationCaseStatus: string
{
    case Received = 'received';
    case Assessment = 'assessment';
    case ServicePlanning = 'service_planning';
    case InService = 'in_service';
    case Monitoring = 'monitoring';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Received => 'Kasus Diterima',
            self::Assessment => 'Tahap Assessment',
            self::ServicePlanning => 'Rencana Pelayanan',
            self::InService => 'Dalam Pelayanan',
            self::Monitoring => 'Monitoring Perkembangan',
            self::Closed => 'Kasus Ditutup (Selesai)',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Received => 'gray',
            self::Assessment, self::ServicePlanning => 'info',
            self::InService => 'primary',
            self::Monitoring => 'warning',
            self::Closed => 'success',
        };
    }
}
