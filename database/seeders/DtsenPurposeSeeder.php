<?php

namespace Database\Seeders;

use App\Models\DtsenPurpose;
use Illuminate\Database\Seeder;

class DtsenPurposeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $purposes = [
            [
                'code' => 'spmb',
                'name' => 'SPMB Jalur Afirmasi',
                'max_decile' => 5,
                'validity_days' => 90,
                'is_active' => true,
            ],
            [
                'code' => 'pip',
                'name' => 'Program Indonesia Pintar (PIP)',
                'max_decile' => 5,
                'validity_days' => 180,
                'is_active' => true,
            ],
            [
                'code' => 'kip_kuliah',
                'name' => 'KIP Kuliah',
                'max_decile' => 4,
                'validity_days' => 180,
                'is_active' => true,
            ],
            [
                'code' => 'bansos',
                'name' => 'Permohonan / Verifikasi Bantuan Sosial',
                'max_decile' => 3,
                'validity_days' => 90,
                'is_active' => true,
            ],
            [
                'code' => 'kesehatan',
                'name' => 'Keringanan Biaya Kesehatan / RS',
                'max_decile' => 4,
                'validity_days' => 90,
                'is_active' => true,
            ],
            [
                'code' => 'lainnya',
                'name' => 'Keperluan Lainnya',
                'max_decile' => 5,
                'validity_days' => 90,
                'is_active' => true,
            ],
        ];

        foreach ($purposes as $p) {
            DtsenPurpose::firstOrCreate(['code' => $p['code']], $p);
        }
    }
}
