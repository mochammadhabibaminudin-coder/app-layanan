<?php

namespace Database\Seeders;

use App\Enums\ClientGender;
use App\Enums\HandlingType;
use App\Enums\ReferralStatus;
use App\Enums\RehabilitationCaseStatus;
use App\Models\Assessment;
use App\Models\Client;
use App\Models\ClientCategory;
use App\Models\MonitoringRecord;
use App\Models\NumberSequence;
use App\Models\Referral;
use App\Models\ReferralInstitution;
use App\Models\RehabilitationCase;
use App\Models\StatusHistory;
use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Seeder;

class RehabilitationSeeder extends Seeder
{
    public function run(): void
    {
        $officer = User::where('email', 'petugas.rehsos@dinsos.blitarkab.go.id')->first();

        $catLansia = ClientCategory::where('name', 'like', '%Lanjut Usia%')->first();
        $catDisabilitas = ClientCategory::where('name', 'like', '%Disabilitas%')->first();
        $catOdgj = ClientCategory::where('name', 'like', '%ODGJ%')->first();
        $catAnak = ClientCategory::where('name', 'like', '%Anak%')->first();

        $kanigoro = Village::where('name', 'Kanigoro')->first();
        $garum = Village::where('name', 'Garum')->first();
        $wlingi = Village::where('name', 'Wlingi')->first();
        $srengat = Village::where('name', 'Srengat')->first();

        $instPstw = ReferralInstitution::where('name', 'like', '%PSTW%')->first();
        $instRsudWlingi = ReferralInstitution::where('name', 'like', '%Ngudi Waluyo%')->first();
        $instBalai = ReferralInstitution::where('name', 'like', '%Balai Rehabilitasi%')->first();

        // 1. KASUS 1: Lansia Terlantar dirujuk ke Panti Sosial (Status: In Service)
        $client1 = Client::create([
            'name' => 'Mbah Marto Wiyono',
            'client_category_id' => $catLansia?->id ?? 1,
            'nik' => '3505011005390001',
            'birth_date' => '1939-05-10',
            'gender' => ClientGender::Male,
            'address' => 'Ditemukan di emperan ruko Pasar Kanigoro, tanpa keluarga',
            'village_id' => $kanigoro?->id,
            'phone' => null,
        ]);

        $case1Number = NumberSequence::generateNext('RHS', now()->subDays(12));
        $case1 = RehabilitationCase::create([
            'case_number' => $case1Number,
            'client_id' => $client1->id,
            'officer_id' => $officer?->id,
            'handling_type' => HandlingType::Referral,
            'status' => RehabilitationCaseStatus::InService,
            'handling_result' => 'Klien telah dirujuk dan diterima di PSTW Blitar untuk mendapatkan perawatan dan permakanan rutin.',
            'received_at' => now()->subDays(12),
            'closed_at' => null,
        ]);

        $assessment1 = Assessment::create([
            'rehabilitation_case_id' => $case1->id,
            'officer_id' => $officer?->id,
            'assessment_date' => now()->subDays(11)->toDateString(),
            'result' => 'Lansia berusia 85 tahun dalam kondisi lemah, tidak memiliki tempat tinggal tetap dan tidak ada keluarga yang mengurus.',
            'service_needs' => 'Kebutuhan tempat tinggal layak, makanan bernutrisi, pemeriksaan kesehatan rutin, dan pendampingan lansia.',
            'recommendation' => 'Direkomendasikan untuk dirujuk ke UPTD Panti Sosial Tresna Werdha (PSTW) Blitar.',
            'needs_referral' => true,
        ]);

        $ref1Number = NumberSequence::generateNext('RJK', now()->subDays(9));
        $referral1 = Referral::create([
            'referral_number' => $ref1Number,
            'rehabilitation_case_id' => $case1->id,
            'assessment_id' => $assessment1->id,
            'referral_institution_id' => $instPstw?->id ?? 1,
            'officer_id' => $officer?->id,
            'referral_date' => now()->subDays(9)->toDateString(),
            'status' => ReferralStatus::InService,
            'service_result' => 'Klien menempati wisma flamboyan PSTW Blitar dan beradaptasi dengan baik.',
            'completed_at' => null,
        ]);

        MonitoringRecord::create([
            'rehabilitation_case_id' => $case1->id,
            'referral_id' => $referral1->id,
            'officer_id' => $officer?->id,
            'monitoring_date' => now()->subDays(3)->toDateString(),
            'progress' => 'Petugas Dinsos melakukan kunjungan ke PSTW. Kondisi kesehatan Mbah Marto membaik, nafsu makan stabil.',
            'result_notes' => 'Pelayanan berjalan optimal di panti tujuan.',
        ]);

        // Status history Kasus 1
        StatusHistory::create([
            'statusable_type' => RehabilitationCase::class,
            'statusable_id' => $case1->id,
            'from_status' => null,
            'to_status' => 'received',
            'notes' => 'Laporan lansia terlantar diterima dari warga Kanigoro.',
            'user_id' => $officer?->id,
            'created_at' => now()->subDays(12),
        ]);
        StatusHistory::create([
            'statusable_type' => RehabilitationCase::class,
            'statusable_id' => $case1->id,
            'from_status' => 'received',
            'to_status' => 'assessment',
            'notes' => 'Assessment awal dilakukan di shelter sementara Dinsos.',
            'user_id' => $officer?->id,
            'created_at' => now()->subDays(11),
        ]);
        StatusHistory::create([
            'statusable_type' => RehabilitationCase::class,
            'statusable_id' => $case1->id,
            'from_status' => 'assessment',
            'to_status' => 'in_service',
            'notes' => 'Rujukan ke PSTW Blitar diterbitkan dan klien diantarkan ke panti.',
            'user_id' => $officer?->id,
            'created_at' => now()->subDays(9),
        ]);

        // 2. KASUS 2: ODGJ Terlantar butuh tindakan medis (Status: In Service)
        $client2 = Client::create([
            'name' => 'Agus (Nama Panggilan)',
            'client_category_id' => $catOdgj?->id ?? 3,
            'nik' => null, // Klien tanpa identitas
            'birth_date' => null,
            'gender' => ClientGender::Male,
            'address' => 'Ditemukan terlantar di Pasar Garum',
            'village_id' => $garum?->id,
            'phone' => null,
        ]);

        $case2Number = NumberSequence::generateNext('RHS', now()->subDays(4));
        $case2 = RehabilitationCase::create([
            'case_number' => $case2Number,
            'client_id' => $client2->id,
            'officer_id' => $officer?->id,
            'handling_type' => HandlingType::Referral,
            'status' => RehabilitationCaseStatus::InService,
            'handling_result' => 'Klien dievakuasi ke RSUD Ngudi Waluyo Wlingi untuk perawatan kejiwaan.',
            'received_at' => now()->subDays(4),
            'closed_at' => null,
        ]);

        $assessment2 = Assessment::create([
            'rehabilitation_case_id' => $case2->id,
            'officer_id' => $officer?->id,
            'assessment_date' => now()->subDays(3)->toDateString(),
            'result' => 'Klien mengalami disorientasi waktu dan tempat, gelisah, berbicara sendiri, tanpa dokumen kependudukan.',
            'service_needs' => 'Penanganan kegawatdaruratan psikiatri dan stabilisasi obat.',
            'recommendation' => 'Rujukan evakuasi medis ke poli jiwa / ruang rawat jiwa RSUD Ngudi Waluyo.',
            'needs_referral' => true,
        ]);

        $ref2Number = NumberSequence::generateNext('RJK', now()->subDays(3));
        Referral::create([
            'referral_number' => $ref2Number,
            'rehabilitation_case_id' => $case2->id,
            'assessment_id' => $assessment2->id,
            'referral_institution_id' => $instRsudWlingi?->id ?? 1,
            'officer_id' => $officer?->id,
            'referral_date' => now()->subDays(3)->toDateString(),
            'status' => ReferralStatus::Accepted,
            'service_result' => 'Pasien dirawat inap di bangsal jiwa RSUD Ngudi Waluyo.',
            'completed_at' => null,
        ]);

        // 3. KASUS 3: Penyandang Disabilitas (Pelayanan Langsung & Selesai / Closed)
        $client3 = Client::create([
            'name' => 'Budi Setiawan',
            'client_category_id' => $catDisabilitas?->id ?? 2,
            'nik' => '3505041804950002',
            'birth_date' => '1995-04-18',
            'gender' => ClientGender::Male,
            'address' => 'Kelurahan Tangkil RT 03 RW 02',
            'village_id' => $wlingi?->id,
            'phone' => '085712345678',
        ]);

        $case3Number = NumberSequence::generateNext('RHS', now()->subDays(25));
        $case3 = RehabilitationCase::create([
            'case_number' => $case3Number,
            'client_id' => $client3->id,
            'officer_id' => $officer?->id,
            'handling_type' => HandlingType::Direct,
            'status' => RehabilitationCaseStatus::Closed,
            'handling_result' => 'Bantuan alat bantu kursi roda dan paket sembako nutrisi telah diserahkan langsung ke rumah klien. Kasus ditutup.',
            'received_at' => now()->subDays(25),
            'closed_at' => now()->subDays(5),
        ]);

        Assessment::create([
            'rehabilitation_case_id' => $case3->id,
            'officer_id' => $officer?->id,
            'assessment_date' => now()->subDays(24)->toDateString(),
            'result' => 'Penyandang disabilitas fisik paraplegia akibat kecelakaan kerja, membutuhkan mobilitas kursi roda.',
            'service_needs' => 'Kursi roda standar dan bantuan kebutuhan pokok.',
            'recommendation' => 'Pemberian alat bantu langsung dari alokasi APBD Dinsos Kabupaten Blitar.',
            'needs_referral' => false,
        ]);

        MonitoringRecord::create([
            'rehabilitation_case_id' => $case3->id,
            'referral_id' => null,
            'officer_id' => $officer?->id,
            'monitoring_date' => now()->subDays(6)->toDateString(),
            'progress' => 'Monitoring pasca penyerahan kursi roda. Klien sudah dapat beraktivitas ringan di lingkungan rumah dengan mandiri.',
            'result_notes' => 'Bantuan bermanfaat optimal, klien dan keluarga merasa terbantu.',
        ]);

        // Status history Kasus 3
        StatusHistory::create([
            'statusable_type' => RehabilitationCase::class,
            'statusable_id' => $case3->id,
            'from_status' => 'monitoring',
            'to_status' => 'closed',
            'notes' => 'Kasus ditutup karena pelayanan langsung dan monitoring telah selesai dilakukan.',
            'user_id' => $officer?->id,
            'created_at' => now()->subDays(5),
        ]);
    }
}
