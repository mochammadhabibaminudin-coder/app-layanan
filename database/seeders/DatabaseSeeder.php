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
            WorkUnitSeeder::class,
            DistrictAndVillageSeeder::class,
            ServiceTypeSeeder::class,
            DtsenPurposeSeeder::class,
            ClientCategorySeeder::class,
            ReferralInstitutionSeeder::class,
            ComplaintCategorySeeder::class,
            UserSeeder::class,
        ]);
    }
}
