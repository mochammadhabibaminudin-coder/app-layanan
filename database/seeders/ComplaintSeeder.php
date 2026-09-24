<?php

namespace Database\Seeders;

use App\Enums\ComplaintAttachmentType;
use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use App\Models\ComplaintCategory;
use App\Models\Disposition;
use App\Models\NumberSequence;
use App\Models\StatusHistory;
use App\Models\User;
use App\Models\Village;
use App\Models\WorkUnit;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    public function run(): void
    {
        if (Complaint::where('reporter_name', 'Agus Wicaksono (Ketua RT)')->exists()) {
            return;
        }

        $catRehsos = ComplaintCategory::where('name', 'like', '%Rehabilitasi%')->first();
        $catBansos = ComplaintCategory::where('name', 'like', '%Bantuan Sosial%')->first();
        $catKemiskinan = ComplaintCategory::where('name', 'like', '%Kemiskinan%')->first();

        $kanigoro = Village::where('name', 'Kanigoro')->first();
        $garum = Village::where('name', 'Garum')->first();
        $srengat = Village::where('name', 'Srengat')->first();

        $linjamsos = WorkUnit::where('name', 'like', '%Linjamsos%')->first();
        $rehsos = WorkUnit::where('name', 'like', '%Rehsos%')->first();

        $officerRehsos = User::where('email', 'petugas.rehsos@dinsos.blitarkab.go.id')->first();
        $officerPelayanan = User::where('email', 'petugas.pelayanan@dinsos.blitarkab.go.id')->first();
        $kabid = User::where('email', 'kabid.linjamsos@dinsos.blitarkab.go.id')->first();

        // 1. Pengaduan Selesai (Lansia terlantar sebatang kara)
        $c1Number = NumberSequence::generateNext('ADU', now()->subDays(10));
        $comp1 = Complaint::create([
            'complaint_number' => $c1Number,
            'complaint_category_id' => $catRehsos?->id ?? 1,
            'reporter_id' => null,
            'reporter_name' => 'Agus Wicaksono (Ketua RT)',
            'reporter_phone' => '081234567811',
            'location_detail' => 'Dusun Gogodeso RT 03 RW 01, belakang Balai Desa',
            'village_id' => $kanigoro?->id ?? 1,
            'description' => 'Ada seorang lansia janda (Mbah Rukmi, 79 th) hidup sebatang kara di gubuk reot yang hampir roboh, sakit-sakitan dan tidak ada yang merawat.',
            'reported_at' => now()->subDays(10),
            'officer_id' => $officerRehsos?->id,
            'status' => ComplaintStatus::Resolved,
            'verification_result' => 'Laporan valid. Petugas Dinsos bersama TKSK dan aparat desa telah mengunjungi lokasi.',
            'action_taken' => 'Telah diberikan bantuan paket sembako darurat, pemeriksaan kesehatan oleh Puskesmas, dan diusulkan program bedah rumah / panti sosial.',
            'duplicate_of_id' => null,
            'resolved_at' => now()->subDays(3),
        ]);

        ComplaintAttachment::create([
            'complaint_id' => $comp1->id,
            'file_path' => 'complaints/kondisi_rumah_mbah_rukmi.jpg',
            'type' => ComplaintAttachmentType::Photo,
        ]);

        // Disposisi aduan 1
        Disposition::create([
            'dispositionable_type' => Complaint::class,
            'dispositionable_id' => $comp1->id,
            'from_user_id' => $kabid?->id ?? 1,
            'to_work_unit_id' => $rehsos?->id ?? 1,
            'to_user_id' => $officerRehsos?->id,
            'instructions' => 'Segera lakukan penjangkauan (outreach) ke lokasi bersama TKSK setempat.',
            'disposed_at' => now()->subDays(9),
        ]);

        StatusHistory::create([
            'statusable_type' => Complaint::class,
            'statusable_id' => $comp1->id,
            'from_status' => null,
            'to_status' => 'received',
            'notes' => 'Pengaduan masuk dari warga melalui portal pengaduan.',
            'user_id' => null,
            'created_at' => now()->subDays(10),
        ]);
        StatusHistory::create([
            'statusable_type' => Complaint::class,
            'statusable_id' => $comp1->id,
            'from_status' => 'received',
            'to_status' => 'dispatched',
            'notes' => 'Laporan didisposisikan ke Bidang Rehabilitasi Sosial.',
            'user_id' => $kabid?->id,
            'created_at' => now()->subDays(9),
        ]);
        StatusHistory::create([
            'statusable_type' => Complaint::class,
            'statusable_id' => $comp1->id,
            'from_status' => 'dispatched',
            'to_status' => 'in_handling',
            'notes' => 'Petugas meluncur ke lokasi untuk penanganan lapangan.',
            'user_id' => $officerRehsos?->id,
            'created_at' => now()->subDays(7),
        ]);
        StatusHistory::create([
            'statusable_type' => Complaint::class,
            'statusable_id' => $comp1->id,
            'from_status' => 'in_handling',
            'to_status' => 'resolved',
            'notes' => 'Penanganan selesai dan hasil penanganan dicatat.',
            'user_id' => $officerRehsos?->id,
            'created_at' => now()->subDays(3),
        ]);

        // 2. Pengaduan Dalam Penanganan (Keluarga rentan belum pernah menerima bansos)
        $c2Number = NumberSequence::generateNext('ADU', now()->subDays(4));
        $comp2 = Complaint::create([
            'complaint_number' => $c2Number,
            'complaint_category_id' => $catBansos?->id ?? 1,
            'reporter_id' => null,
            'reporter_name' => 'Bambang Eko',
            'reporter_phone' => '085233445566',
            'location_detail' => 'Dusun Bence RT 02 RW 01, Garum',
            'village_id' => $garum?->id ?? 1,
            'description' => 'Tetangga kami Pak Subari menderita stroke, anak 3 masih sekolah, belum pernah dapat bansos PKH atau BPNT sama sekali.',
            'reported_at' => now()->subDays(4),
            'officer_id' => $officerPelayanan?->id,
            'status' => ComplaintStatus::InHandling,
            'verification_result' => 'Data dicek di SIKS-NG, NIK terdaftar desil 1 namun belum masuk kuota bansos reguler.',
            'action_taken' => 'Sedang dikoordinasikan dengan operator SIKS-NG desa untuk pengusulan bansos daerah.',
            'duplicate_of_id' => null,
            'resolved_at' => null,
        ]);

        ComplaintAttachment::create([
            'complaint_id' => $comp2->id,
            'file_path' => 'complaints/bukti_ktp_kk_warga.jpg',
            'type' => ComplaintAttachmentType::Document,
        ]);

        // 3. Pengaduan Duplikat (Warga lain melaporkan hal yang sama untuk Pak Subari)
        $c3Number = NumberSequence::generateNext('ADU', now()->subDays(2));
        Complaint::create([
            'complaint_number' => $c3Number,
            'complaint_category_id' => $catBansos?->id ?? 1,
            'reporter_id' => null,
            'reporter_name' => 'Tri Wahyuni',
            'reporter_phone' => '087711223344',
            'location_detail' => 'Dusun Bence Garum',
            'village_id' => $garum?->id ?? 1,
            'description' => 'Mohon bantuan untuk keluarga Pak Subari di Dusun Bence, kondisi sangat memprihatinkan.',
            'reported_at' => now()->subDays(2),
            'officer_id' => $officerPelayanan?->id,
            'status' => ComplaintStatus::Duplicate,
            'verification_result' => 'Laporan sama dengan aduan '.$c2Number.'.',
            'action_taken' => 'Digabungkan ke laporan induk '.$c2Number.'.',
            'duplicate_of_id' => $comp2->id,
            'resolved_at' => now()->subDay(),
        ]);

        // 4. Pengaduan Baru Diterima
        $c4Number = NumberSequence::generateNext('ADU', now()->subHours(5));
        Complaint::create([
            'complaint_number' => $c4Number,
            'complaint_category_id' => $catKemiskinan?->id ?? 1,
            'reporter_id' => null,
            'reporter_name' => 'Hadi Sucipto',
            'reporter_phone' => '081344556677',
            'location_detail' => 'Jl. Kauman Gang 3, Srengat',
            'village_id' => $srengat?->id ?? 1,
            'description' => 'Ada anak yatim usia 13 tahun putus sekolah karena tidak ada biaya membeli seragam dan peralatan.',
            'reported_at' => now()->subHours(5),
            'officer_id' => null,
            'status' => ComplaintStatus::Received,
            'verification_result' => null,
            'action_taken' => null,
            'duplicate_of_id' => null,
            'resolved_at' => null,
        ]);
    }
}
