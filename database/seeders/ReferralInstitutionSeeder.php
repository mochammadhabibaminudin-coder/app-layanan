<?php

namespace Database\Seeders;

use App\Models\ReferralInstitution;
use Illuminate\Database\Seeder;

class ReferralInstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institutions = [
            [
                'name' => 'RSUD Ngudi Waluyo Wlingi',
                'type' => 'RS',
                'address' => 'Jl. Dokter Sucipto No.5, Beru, Wlingi, Kabupaten Blitar',
                'contact' => '(0342) 691006',
                'is_active' => true,
            ],
            [
                'name' => 'RSUD Srengat Blitar',
                'type' => 'RS',
                'address' => 'Jl. Raya Dandong No.1, Srengat, Kabupaten Blitar',
                'contact' => '(0342) 562111',
                'is_active' => true,
            ],
            [
                'name' => 'UPTD PSTW (Panti Sosial Tresna Werdha) Blitar',
                'type' => 'panti',
                'address' => 'Jl. Merdeka No. 45, Blitar',
                'contact' => '(0342) 801234',
                'is_active' => true,
            ],
            [
                'name' => 'Balai Rehabilitasi Sosial Anak dan Disabilitas Jawa Timur',
                'type' => 'balai',
                'address' => 'Surabaya / Sidoarjo',
                'contact' => '(031) 8921234',
                'is_active' => true,
            ],
            [
                'name' => 'Lembaga Kesejahteraan Sosial (LKS) Kasih Sejahtera Blitar',
                'type' => 'LKS',
                'address' => 'Kanigoro, Kabupaten Blitar',
                'contact' => '081234567890',
                'is_active' => true,
            ],
        ];

        foreach ($institutions as $inst) {
            ReferralInstitution::firstOrCreate(['name' => $inst['name']], $inst);
        }
    }
}
