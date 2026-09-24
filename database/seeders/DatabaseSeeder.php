<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // 1. Master Data Dasar & Pengguna
            WorkUnitSeeder::class,
            DistrictAndVillageSeeder::class,
            ServiceTypeSeeder::class,
            DtsenPurposeSeeder::class,
            ClientCategorySeeder::class,
            ReferralInstitutionSeeder::class,
            ComplaintCategorySeeder::class,
            UserSeeder::class,

            // 2. Data Transaksi Layanan PRD (Layanan 1 - 6)
            DtsenServiceRequestSeeder::class,
            PbiServiceRequestSeeder::class,
            RehabilitationSeeder::class,
            GeneralServiceRequestSeeder::class,
            ComplaintSeeder::class,
            InformationPortalSeeder::class,
        ]);
    }
}
