<?php

namespace Database\Seeders;

use App\Models\ComplaintCategory;
use Illuminate\Database\Seeder;

class ComplaintCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Kemiskinan & Kerentanan Sosial',
            'Kebutuhan Rehabilitasi Sosial & Klien Terlantar',
            'Kendala / Dugaan Masalah Penyaluran Bantuan Sosial',
            'Kepesertaan JKN-KIS / BPJS PBI Nonaktif',
            'Penyandang Disabilitas & Kebutuhan Alat Bantu',
            'Kedaruratan Sosial / Korban Kekerasan',
            'Lain-lain',
        ];

        foreach ($categories as $name) {
            ComplaintCategory::firstOrCreate(['name' => $name], ['is_active' => true]);
        }
    }
}
