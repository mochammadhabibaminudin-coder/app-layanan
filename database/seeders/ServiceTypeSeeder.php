<?php

namespace Database\Seeders;

use App\Enums\ServiceHandler;
use App\Models\ServiceRequirement;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'code' => 'DTSEN',
                'name' => 'Surat Keterangan DTSEN',
                'category' => 'Jaminan Sosial',
                'description' => 'Penerbitan surat keterangan status pemohon/keluarga dalam Data Tunggal Sosial Ekonomi Nasional (DTSEN) dan peringkat desil.',
                'handler' => ServiceHandler::Dtsen,
                'needs_assessment' => false,
                'sla_days' => 1,
                'requirements' => [
                    ['name' => 'Kartu Tanda Penduduk (KTP)', 'is_mandatory' => true, 'allowed_mimes' => 'pdf,jpg,png,jpeg', 'sort_order' => 1],
                    ['name' => 'Kartu Keluarga (KK)', 'is_mandatory' => true, 'allowed_mimes' => 'pdf,jpg,png,jpeg', 'sort_order' => 2],
                    ['name' => 'Surat Permohonan / Rekomendasi Sekolah (opsional)', 'is_mandatory' => false, 'allowed_mimes' => 'pdf,jpg,png,jpeg', 'sort_order' => 3],
                ],
            ],
            [
                'code' => 'PBI',
                'name' => 'Reaktivasi KIS / PBI-JK',
                'category' => 'Jaminan Kesehatan',
                'description' => 'Fasilitasi pengaktifan kembali kepesertaan JKN-KIS Penerima Bantuan Iuran Jaminan Kesehatan (PBI-JK) yang dinonaktifkan.',
                'handler' => ServiceHandler::Pbi,
                'needs_assessment' => false,
                'sla_days' => 3,
                'requirements' => [
                    ['name' => 'Kartu Tanda Penduduk (KTP)', 'is_mandatory' => true, 'allowed_mimes' => 'pdf,jpg,png,jpeg', 'sort_order' => 1],
                    ['name' => 'Kartu Keluarga (KK)', 'is_mandatory' => true, 'allowed_mimes' => 'pdf,jpg,png,jpeg', 'sort_order' => 2],
                    ['name' => 'Kartu BPJS Kesehatan / KIS', 'is_mandatory' => true, 'allowed_mimes' => 'pdf,jpg,png,jpeg', 'sort_order' => 3],
                    ['name' => 'Surat Keterangan Fasilitas Kesehatan / RS (Wajib alasan medis)', 'is_mandatory' => true, 'allowed_mimes' => 'pdf,jpg,png,jpeg', 'sort_order' => 4],
                ],
            ],
            [
                'code' => 'REHSOS',
                'name' => 'Pelayanan Rehabilitasi Sosial',
                'category' => 'Rehabilitasi Sosial',
                'description' => 'Penanganan klien dan warga yang membutuhkan pelayanan atau rujukan rehabilitasi sosial (lansia terlantar, disabilitas, ODGJ, anak, korban kekerasan).',
                'handler' => ServiceHandler::Generic,
                'needs_assessment' => true,
                'sla_days' => 7,
                'requirements' => [
                    ['name' => 'Identitas Klien / KTP / KK (bila ada)', 'is_mandatory' => false, 'allowed_mimes' => 'pdf,jpg,png,jpeg', 'sort_order' => 1],
                    ['name' => 'Foto Kondisi Klien / Bukti Permasalahan', 'is_mandatory' => true, 'allowed_mimes' => 'jpg,png,jpeg', 'sort_order' => 2],
                    ['name' => 'Surat Pengantar Desa / Kecamatan / Laporan Polisi (bila ada)', 'is_mandatory' => false, 'allowed_mimes' => 'pdf,jpg,png,jpeg', 'sort_order' => 3],
                ],
            ],
            [
                'code' => 'REK_BANSOS',
                'name' => 'Rekomendasi Bantuan Sosial',
                'category' => 'Bantuan Sosial',
                'description' => 'Pengajuan surat rekomendasi bantuan sosial terencana atau insidentil untuk masyarakat kurang mampu.',
                'handler' => ServiceHandler::Generic,
                'needs_assessment' => true,
                'sla_days' => 5,
                'requirements' => [
                    ['name' => 'KTP Pemohon', 'is_mandatory' => true, 'allowed_mimes' => 'pdf,jpg,png,jpeg', 'sort_order' => 1],
                    ['name' => 'Kartu Keluarga (KK)', 'is_mandatory' => true, 'allowed_mimes' => 'pdf,jpg,png,jpeg', 'sort_order' => 2],
                    ['name' => 'Surat Keterangan Tidak Mampu (SKTM) dari Desa/Kelurahan', 'is_mandatory' => true, 'allowed_mimes' => 'pdf,jpg,png,jpeg', 'sort_order' => 3],
                    ['name' => 'Foto Rumah / Kondisi Ekonomi', 'is_mandatory' => true, 'allowed_mimes' => 'jpg,png,jpeg', 'sort_order' => 4],
                ],
            ],
        ];

        foreach ($services as $srv) {
            $requirements = $srv['requirements'];
            unset($srv['requirements']);

            $serviceType = ServiceType::firstOrCreate(
                ['code' => $srv['code']],
                $srv
            );

            foreach ($requirements as $req) {
                ServiceRequirement::firstOrCreate(
                    [
                        'service_type_id' => $serviceType->id,
                        'name' => $req['name'],
                    ],
                    $req
                );
            }
        }
    }
}
