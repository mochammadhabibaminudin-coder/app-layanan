<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\User;
use App\Models\Village;
use App\Models\WorkUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $linjamsos = WorkUnit::where('name', 'like', '%Linjamsos%')->first();
        $rehsos = WorkUnit::where('name', 'like', '%Rehsos%')->first();
        $sekretariat = WorkUnit::where('name', 'like', '%Sekretariat%')->first();

        $kanigoroDistrict = District::where('name', 'Kanigoro')->first();
        $kanigoroVillage = Village::where('name', 'Kanigoro')->first();

        $users = [
            [
                'name' => 'Administrator Sistem',
                'email' => 'admin@dinsos.blitarkab.go.id',
                'phone' => '081234567801',
                'nik' => '3505010101900001',
                'work_unit_id' => $sekretariat?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Ahmad Muzakki (Petugas Pelayanan & SIKS-NG)',
                'email' => 'petugas.pelayanan@dinsos.blitarkab.go.id',
                'phone' => '081234567802',
                'nik' => '3505010101900002',
                'work_unit_id' => $linjamsos?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Siti Rahmawati (Petugas Rehabilitasi Sosial)',
                'email' => 'petugas.rehsos@dinsos.blitarkab.go.id',
                'phone' => '081234567803',
                'nik' => '3505010101900003',
                'work_unit_id' => $rehsos?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Budi Santoso, S.Sos (Kepala Bidang Linjamsos)',
                'email' => 'kabid.linjamsos@dinsos.blitarkab.go.id',
                'phone' => '081234567804',
                'nik' => '3505010101800001',
                'work_unit_id' => $linjamsos?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Drs. Bambang Wijaya, M.Si (Kepala Dinas Sosial)',
                'email' => 'kadis@dinsos.blitarkab.go.id',
                'phone' => '081234567805',
                'nik' => '3505010101750001',
                'work_unit_id' => $sekretariat?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Wahyu Hidayat (Operator Kecamatan Kanigoro)',
                'email' => 'operator.kanigoro@blitarkab.go.id',
                'phone' => '081234567806',
                'nik' => '3505010101950001',
                'district_id' => $kanigoroDistrict?->id,
                'village_id' => $kanigoroVillage?->id,
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ])
            );
        }
    }
}
