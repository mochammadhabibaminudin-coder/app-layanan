<?php

namespace App\Enums;

enum InformationPageCategory: string
{
    case Program = 'program';
    case Rehabilitation = 'rehabilitation';
    case Disability = 'disability';
    case Elderly = 'elderly';
    case Complaint = 'complaint';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Program => 'Program Sosial',
            self::Rehabilitation => 'Rehabilitasi Sosial',
            self::Disability => 'Penyandang Disabilitas',
            self::Elderly => 'Lanjut Usia (Lansia)',
            self::Complaint => 'Pengaduan Sosial',
            self::Other => 'Informasi Lainnya',
        };
    }
}
