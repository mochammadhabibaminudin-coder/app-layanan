<?php

namespace App\Enums;

enum ReferralInstitutionType: string
{
    case Panti = 'panti';
    case Balai = 'balai';
    case Hospital = 'RS';
    case Lks = 'LKS';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Panti => 'Panti Sosial',
            self::Balai => 'Balai Rehabilitasi',
            self::Hospital => 'Rumah Sakit / Fasilitas Kesehatan',
            self::Lks => 'Lembaga Kesejahteraan Sosial (LKS)',
            self::Other => 'Lainnya',
        };
    }
}
