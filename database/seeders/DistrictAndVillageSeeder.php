<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Village;
use Illuminate\Database\Seeder;

class DistrictAndVillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            ['code' => '35.05.01', 'name' => 'Kanigoro', 'villages' => ['Kanigoro', 'Kuningan', 'Satreyan', 'Tlogo', 'Gogodeso', 'Jatinom', 'Karangsono', 'Bangle', 'Sawentar', 'Papungan']],
            ['code' => '35.05.02', 'name' => 'Garum', 'villages' => ['Garum', 'Tawangsari', 'Bence', 'Pojok', 'Slorok', 'Karangrejo', 'Tingal', 'Sidodadi', 'Tumbrasanom']],
            ['code' => '35.05.03', 'name' => 'Sutojayan', 'villages' => ['Kalipang', 'Sutojayan', 'Jingglong', 'Sukorejo', 'Pandanyoyo', 'Kembangarum', 'Bacem', 'Kaulon']],
            ['code' => '35.05.04', 'name' => 'Wlingi', 'villages' => ['Wlingi', 'Babadan', 'Beru', 'Klemunan', 'Tangkil', 'Tegalasri', 'Balerejo', 'Tembalang', 'Ngadirenggo']],
            ['code' => '35.05.05', 'name' => 'Srengat', 'villages' => ['Srengat', 'Kauman', 'Togogan', 'Dandong', 'Bagelenan', 'Kandangan', 'Karanggayam', 'Purwokerto', 'Selokajang']],
            ['code' => '35.05.06', 'name' => 'Kademangan', 'villages' => ['Kademangan', 'Rejotangan', 'Darungan', 'Plosorejo', 'Panggungduwet', 'Sumberjati', 'Jimbe', 'Bendosari']],
            ['code' => '35.05.07', 'name' => 'Gandusari', 'villages' => ['Gandusari', 'Sukosewu', 'Kotes', 'Gadungan', 'Tulungrejo', 'Semin', 'Slumbung', 'Krisik']],
            ['code' => '35.05.08', 'name' => 'Kesamben', 'villages' => ['Kesamben', 'Siraman', 'Jugol', 'Pagergunung', 'Tapakrejo', 'Bumiayu', 'Pagersari']],
            ['code' => '35.05.09', 'name' => 'Nglegok', 'villages' => ['Nglegok', 'Ngoran', 'Modangan', 'Penataran', 'Kedawung', 'Krenceng', 'Jiwo', 'Dayu']],
            ['code' => '35.05.10', 'name' => 'Sanankulon', 'villages' => ['Sanankulon', 'Bendowulung', 'Purworejo', 'Kalipucung', 'Gleduk', 'Sumberjo', 'Plosoarang']],
            ['code' => '35.05.11', 'name' => 'Ponggok', 'villages' => ['Ponggok', 'Candirejo', 'Kawedusan', 'Dadapanyar', 'Gembongan', 'Maliran', 'Ringinanyar', 'Sidorejo']],
            ['code' => '35.05.12', 'name' => 'Udanawu', 'villages' => ['Bakung', 'Besuki', 'Karanggondang', 'Mangunan', 'Ringinanom', 'Sukamulyo', 'Temenggungan']],
            ['code' => '35.05.13', 'name' => 'Wonodadi', 'villages' => ['Wonodadi', 'Gandekan', 'Kolomayan', 'Kunir', 'Pikatan', 'Rejosari', 'Salam', 'Tawangrejo']],
            ['code' => '35.05.14', 'name' => 'Talun', 'villages' => ['Talun', 'Kaweron', 'Kamulan', 'Duren', 'Jeblog', 'Kendaldoyong', 'Pasirharjo', 'Sragi']],
            ['code' => '35.05.15', 'name' => 'Bakung', 'villages' => ['Bakung', 'Kedungbanteng', 'Lorejo', 'Ngrejo', 'Plandirejo', 'Pulerejo', 'Sidomulyo', 'Tumpakkepuh']],
            ['code' => '35.05.16', 'name' => 'Binangun', 'villages' => ['Binangun', 'Birowo', 'Kedungwungu', 'Ngadri', 'Rejoso', 'Sambigede', 'Sukorame', 'Sumberkembar']],
            ['code' => '35.05.17', 'name' => 'Doko', 'villages' => ['Doko', 'Genengan', 'Jambepawon', 'Kalimanis', 'Plumbangan', 'Resapombo', 'Sidoasri', 'Suru']],
            ['code' => '35.05.18', 'name' => 'Panggungrejo', 'villages' => ['Panggungrejo', 'Balerejo', 'Bumiayu', 'Kaligambir', 'Kalitengah', 'Margomulyo', 'Serang', 'Sumberagung']],
            ['code' => '35.05.19', 'name' => 'Selopuro', 'villages' => ['Selopuro', 'Jambewangi', 'Jatitengah', 'Mandisan', 'Mlandingan', 'Ploso', 'Popoh', 'Tegalrejo']],
            ['code' => '35.05.20', 'name' => 'Selorejo', 'villages' => ['Selorejo', 'Banjarsari', 'Boro', 'Ngrendeng', 'Olakkalen', 'Pohgajih', 'Sidomulyo', 'Sumberagung']],
            ['code' => '35.05.21', 'name' => 'Wates', 'villages' => ['Wates', 'Mojorejo', 'Purworejo', 'Ringinrejo', 'Sukorejo', 'Tugurejo']],
            ['code' => '35.05.22', 'name' => 'Wonotirto', 'villages' => ['Wonotirto', 'Gununggede', 'Kaligrenjeng', 'Ngadipuro', 'Pasiraman', 'Tambakrejo', 'Sumberboto']],
        ];

        foreach ($districts as $d) {
            $district = District::firstOrCreate(
                ['code' => $d['code']],
                ['name' => $d['name']]
            );

            foreach ($d['villages'] as $index => $villageName) {
                $vCode = sprintf('%s.%04d', $d['code'], $index + 1);
                Village::firstOrCreate(
                    ['code' => $vCode],
                    [
                        'district_id' => $district->id,
                        'name' => $villageName,
                    ]
                );
            }
        }
    }
}
