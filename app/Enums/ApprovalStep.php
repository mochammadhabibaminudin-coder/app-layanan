<?php

namespace App\Enums;

enum ApprovalStep: int
{
    case Kabid = 1;
    case Kadis = 2;

    public function label(): string
    {
        return match ($this) {
            self::Kabid => 'Paraf Kepala Bidang',
            self::Kadis => 'Tanda Tangan Kepala Dinas',
        };
    }
}
