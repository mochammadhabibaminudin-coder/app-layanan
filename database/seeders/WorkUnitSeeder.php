<?php

namespace Database\Seeders;

use App\Models\WorkUnit;
use Illuminate\Database\Seeder;

class WorkUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            'Sekretariat',
            'Bidang Perlindungan dan Jaminan Sosial (Linjamsos)',
            'Bidang Rehabilitasi Sosial (Rehsos)',
            'Bidang Pemberdayaan Sosial dan Penanganan Fakir Miskin (Dayasos)',
        ];

        foreach ($units as $name) {
            WorkUnit::firstOrCreate(['name' => $name], ['is_active' => true]);
        }
    }
}
