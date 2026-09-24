<?php

namespace Database\Seeders;

use App\Models\ClientCategory;
use Illuminate\Database\Seeder;

class ClientCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Lanjut Usia Terlantar (Lansia)',
            'Penyandang Disabilitas Terlantar / Rentan',
            'Orang Dengan Gangguan Jiwa (ODGJ) Terlantar',
            'Anak Terlantar / Memerlukan Perlindungan Khusus',
            'Korban Tindak Kekerasan (KDRT / Eksploitasi)',
            'Gelandangan dan Pengemis (Gepeng)',
            'Korban Bencana Sosial / Pengungsi',
        ];

        foreach ($categories as $name) {
            ClientCategory::firstOrCreate(['name' => $name], ['is_active' => true]);
        }
    }
}
