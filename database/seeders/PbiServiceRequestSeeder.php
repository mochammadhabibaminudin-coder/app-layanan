<?php

namespace Database\Seeders;

use App\Enums\ApprovalDecision;
use App\Enums\ApprovalStep;
use App\Enums\DocumentVerificationStatus;
use App\Enums\MinistryDecision;
use App\Enums\PbiReactivationReason;
use App\Enums\ServiceRequestStatus;
use App\Models\Approval;
use App\Models\NumberSequence;
use App\Models\PbiReactivation;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestDocument;
use App\Models\ServiceType;
use App\Models\StatusHistory;
use App\Models\User;
use App\Models\Village;
use App\Models\WorkUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PbiServiceRequestSeeder extends Seeder
{
    public function run(): void
    {
        $pbiType = ServiceType::where('code', 'PBI')->first();
        if (! $pbiType) {
            return;
        }

        $workUnit = WorkUnit::where('name', 'like', '%Linjamsos%')->first();
        $officer = User::where('email', 'petugas.pelayanan@dinsos.blitarkab.go.id')->first();
        $kabid = User::where('email', 'kabid.linjamsos@dinsos.blitarkab.go.id')->first();
        $kadis = User::where('email', 'kadis@dinsos.blitarkab.go.id')->first();

        $wlingi = Village::where('name', 'Wlingi')->first();
        $srengat = Village::where('name', 'Srengat')->first();
        $kanigoro = Village::where('name', 'Kanigoro')->first();
        $kesamben = Village::where('name', 'Kesamben')->first();

        $requirements = $pbiType->requirements;

        $samples = [
            [
                'status' => ServiceRequestStatus::Completed,
                'is_priority' => false,
                'applicant_name' => 'Kusnan',
                'applicant_nik' => '3505041402650001',
                'family_card_number' => '3505041005080002',
                'address' => 'Kelurahan Babadan RT 02 RW 01',
                'village_id' => $wlingi?->id,
                'phone' => '081233445566',
                'submitted_at' => now()->subDays(20),
                'completed_at' => now()->subDays(2),
                'participant_name' => 'Kusnan',
                'participant_nik' => '3505041402650001',
                'bpjs_card_number' => '0001234567891',
                'deactivated_date' => now()->subMonths(2)->toDateString(),
                'reason' => PbiReactivationReason::Chronic,
                'health_facility_name' => 'Puskesmas Wlingi',
                'health_letter_number' => '440/102/PKM-WLI/2026',
                'decile' => 2,
                'eligibility_notes' => 'Layak direaktivasi, masuk desil 2 dan rutin kontrol penyakit diabetes melitus.',
                'rec_number' => '400.9/045/409.105/REK-PBI/2026',
                'rec_issued_at' => now()->subDays(18),
                'proposed_at' => now()->subDays(17),
                'ministry_decision' => MinistryDecision::Approved,
                'ministry_decided_at' => now()->subDays(5),
                'reactivated_date' => now()->subDays(2)->toDateString(),
                'history' => [
                    ['from' => null, 'to' => 'submitted', 'notes' => 'Permohonan diajukan oleh pemohon.'],
                    ['from' => 'submitted', 'to' => 'document_check', 'notes' => 'Berkas persyaratan diverifikasi lengkap.'],
                    ['from' => 'document_check', 'to' => 'eligibility_verification', 'notes' => 'Cek kelayakan: Masuk Desil 2, terverifikasi layak.'],
                    ['from' => 'eligibility_verification', 'to' => 'awaiting_approval', 'notes' => 'Draf surat rekomendasi dibuat.'],
                    ['from' => 'awaiting_approval', 'to' => 'recommendation_issued', 'notes' => 'Rekomendasi disetujui Kepala Dinas.'],
                    ['from' => 'recommendation_issued', 'to' => 'proposed_to_ministry', 'notes' => 'Usulan diinput ke SIKS-NG oleh petugas pelayanan.'],
                    ['from' => 'proposed_to_ministry', 'to' => 'ministry_approved', 'notes' => 'Usulan disetujui oleh Kementerian Sosial RI.'],
                    ['from' => 'ministry_approved', 'to' => 'reactivated', 'notes' => 'Kepesertaan telah aktif kembali di sistem BPJS Kesehatan.'],
                    ['from' => 'reactivated', 'to' => 'completed', 'notes' => 'Layanan selesai, pemohon diberi tahu.'],
                ],
                'approved' => true,
            ],
            [
                'status' => ServiceRequestStatus::EligibilityVerification,
                'is_priority' => true, // DARURAT MEDIS
                'applicant_name' => 'Wahyudi Pratama',
                'applicant_nik' => '3505051908900002',
                'family_card_number' => '3505051010150003',
                'address' => 'Jl. Kenanga No. 5, Kauman',
                'village_id' => $srengat?->id,
                'phone' => '082211223344',
                'submitted_at' => now()->subHours(6),
                'completed_at' => null,
                'participant_name' => 'Siti Aminah',
                'participant_nik' => '3505055507920003',
                'bpjs_card_number' => '0001987654321',
                'deactivated_date' => now()->subMonth()->toDateString(),
                'reason' => PbiReactivationReason::Emergency,
                'health_facility_name' => 'RSUD Ngudi Waluyo Wlingi',
                'health_letter_number' => '445/890/RSUD-NW/2026',
                'decile' => 1,
                'eligibility_notes' => 'Kondisi darurat medis: Pasien gagal ginjal kronik butuh tindakan cuci darah segera. Diprioritaskan.',
                'rec_number' => null,
                'rec_issued_at' => null,
                'proposed_at' => null,
                'ministry_decision' => MinistryDecision::Pending,
                'ministry_decided_at' => null,
                'reactivated_date' => null,
                'history' => [
                    ['from' => null, 'to' => 'submitted', 'notes' => 'Pengajuan darurat medis diterima sistem.'],
                    ['from' => 'submitted', 'to' => 'document_check', 'notes' => 'Surat opname RS dan berkas darurat lengkap.'],
                    ['from' => 'document_check', 'to' => 'eligibility_verification', 'notes' => 'Petugas melakukan verifikasi cepat desil dan kelayakan.'],
                ],
                'approved' => false,
            ],
            [
                'status' => ServiceRequestStatus::ProposedToMinistry,
                'is_priority' => false,
                'applicant_name' => 'Nurul Hidayati',
                'applicant_nik' => '3505016209880004',
                'family_card_number' => '3505011402120006',
                'address' => 'Dusun Kuningan RT 01 RW 04',
                'village_id' => $kanigoro?->id,
                'phone' => '085811223344',
                'submitted_at' => now()->subDays(16), // Tertahan > 14 hari
                'completed_at' => null,
                'participant_name' => 'Muhammad Zidan',
                'participant_nik' => '3505012010250001',
                'bpjs_card_number' => '0002134567812',
                'deactivated_date' => now()->subMonths(3)->toDateString(),
                'reason' => PbiReactivationReason::Newborn,
                'health_facility_name' => 'RSUD Srengat',
                'health_letter_number' => '445/210/RS-SRG/2026',
                'decile' => 2,
                'eligibility_notes' => 'Bayi baru lahir dari ibu peserta PBI-JK aktif.',
                'rec_number' => '400.9/051/409.105/REK-PBI/2026',
                'rec_issued_at' => now()->subDays(15),
                'proposed_at' => now()->subDays(15), // Diusulkan 15 hari lalu
                'ministry_decision' => MinistryDecision::Pending,
                'ministry_decided_at' => null,
                'reactivated_date' => null,
                'history' => [
                    ['from' => null, 'to' => 'submitted', 'notes' => 'Pengajuan terkirim.'],
                    ['from' => 'submitted', 'to' => 'document_check', 'notes' => 'Berkas lengkap.'],
                    ['from' => 'document_check', 'to' => 'eligibility_verification', 'notes' => 'Memenuhi syarat bayi baru lahir dari keluarga PBI.'],
                    ['from' => 'eligibility_verification', 'to' => 'awaiting_approval', 'notes' => 'Draf rekomendasi dinaikkan.'],
                    ['from' => 'awaiting_approval', 'to' => 'recommendation_issued', 'notes' => 'Rekomendasi terbit.'],
                    ['from' => 'recommendation_issued', 'to' => 'proposed_to_ministry', 'notes' => 'Telah diusulkan ke SIKS-NG Kemensos (menunggu respon pusat).'],
                ],
                'approved' => true,
            ],
            [
                'status' => ServiceRequestStatus::MinistryRejected,
                'is_priority' => false,
                'applicant_name' => 'Subandi',
                'applicant_nik' => '3505081105750001',
                'family_card_number' => '3505081203040001',
                'address' => 'Desa Siraman RT 02 RW 03',
                'village_id' => $kesamben?->id,
                'phone' => '087766554433',
                'submitted_at' => now()->subDays(30),
                'completed_at' => now()->subDays(10),
                'participant_name' => 'Subandi',
                'participant_nik' => '3505081105750001',
                'bpjs_card_number' => '0001889922334',
                'deactivated_date' => now()->subMonths(10)->toDateString(),
                'reason' => PbiReactivationReason::Other,
                'health_facility_name' => 'Puskesmas Kesamben',
                'health_letter_number' => '440/089/PKM-KSB/2026',
                'decile' => 6,
                'eligibility_notes' => 'Masa nonaktif melebihi batas waktu 6 bulan dan desil 6.',
                'rec_number' => '400.9/032/409.105/REK-PBI/2026',
                'rec_issued_at' => now()->subDays(28),
                'proposed_at' => now()->subDays(27),
                'ministry_decision' => MinistryDecision::Rejected,
                'ministry_decided_at' => now()->subDays(10),
                'reactivated_date' => null,
                'history' => [
                    ['from' => null, 'to' => 'submitted', 'notes' => 'Pengajuan terkirim.'],
                    ['from' => 'submitted', 'to' => 'document_check', 'notes' => 'Berkas lengkap.'],
                    ['from' => 'document_check', 'to' => 'eligibility_verification', 'notes' => 'Verifikasi kelayakan dilakukan.'],
                    ['from' => 'eligibility_verification', 'to' => 'recommendation_issued', 'notes' => 'Rekomendasi diterbitkan.'],
                    ['from' => 'recommendation_issued', 'to' => 'proposed_to_ministry', 'notes' => 'Diusulkan ke Kemensos.'],
                    ['from' => 'proposed_to_ministry', 'to' => 'ministry_rejected', 'notes' => 'Ditolak Kemensos: Kepesertaan nonaktif melebihi batas waktu maksimal ketentuan pusat dan data tergolong mampu.'],
                ],
                'approved' => true,
            ],
        ];

        foreach ($samples as $sample) {
            if (ServiceRequest::where('service_type_id', $pbiType->id)->where('applicant_nik', $sample['applicant_nik'])->exists()) {
                continue;
            }

            if (! empty($sample['rec_number']) && PbiReactivation::where('recommendation_number', $sample['rec_number'])->exists()) {
                continue;
            }

            $reqNumber = NumberSequence::generateNext('PBI', $sample['submitted_at']);

            $request = ServiceRequest::create([
                'request_number' => $reqNumber,
                'service_type_id' => $pbiType->id,
                'submitter_id' => null,
                'applicant_name' => $sample['applicant_name'],
                'applicant_nik' => $sample['applicant_nik'],
                'family_card_number' => $sample['family_card_number'],
                'address' => $sample['address'],
                'village_id' => $sample['village_id'] ?? 1,
                'phone' => $sample['phone'],
                'submitted_at' => $sample['submitted_at'],
                'officer_id' => $officer?->id,
                'work_unit_id' => $workUnit?->id,
                'status' => $sample['status'],
                'is_priority' => $sample['is_priority'],
                'verification_result' => $sample['eligibility_notes'],
                'officer_notes' => $sample['is_priority'] ? 'Prioritas darurat medis (butuh hemodialisa)' : 'Proses verifikasi standar',
                'service_result' => $sample['rec_number'] ? ('Rekomendasi Terbit: '.$sample['rec_number']) : null,
                'rejection_reason' => $sample['status'] === ServiceRequestStatus::MinistryRejected ? 'Ditolak Kemensos karena kriteria desil dan masa nonaktif melebihi batas.' : null,
                'completed_at' => $sample['completed_at'],
            ]);

            // Dokumen pengajuan
            foreach ($requirements as $req) {
                ServiceRequestDocument::create([
                    'service_request_id' => $request->id,
                    'service_requirement_id' => $req->id,
                    'file_path' => 'documents/pbi/'.Str::slug($req->name).'_sample.pdf',
                    'original_name' => $req->name.' - '.$sample['applicant_name'].'.pdf',
                    'verification_status' => DocumentVerificationStatus::Valid,
                    'notes' => 'Lengkap dan terverifikasi',
                ]);
            }

            // Detail Reaktivasi PBI
            $pbi = PbiReactivation::create([
                'service_request_id' => $request->id,
                'participant_name' => $sample['participant_name'],
                'participant_nik' => $sample['participant_nik'],
                'bpjs_card_number' => $sample['bpjs_card_number'],
                'deactivated_date' => $sample['deactivated_date'],
                'reason' => $sample['reason'],
                'health_facility_name' => $sample['health_facility_name'],
                'health_letter_number' => $sample['health_letter_number'],
                'decile' => $sample['decile'],
                'eligibility_notes' => $sample['eligibility_notes'],
                'recommendation_number' => $sample['rec_number'],
                'recommendation_issued_at' => $sample['rec_issued_at'],
                'signer_id' => $sample['rec_number'] ? $kadis?->id : null,
                'proposed_to_ministry_at' => $sample['proposed_at'],
                'ministry_decision' => $sample['ministry_decision'],
                'ministry_decided_at' => $sample['ministry_decided_at'],
                'reactivated_date' => $sample['reactivated_date'],
            ]);

            // Persetujuan bila ada
            if ($sample['approved']) {
                Approval::create([
                    'approvable_type' => PbiReactivation::class,
                    'approvable_id' => $pbi->id,
                    'step' => ApprovalStep::Kabid,
                    'approver_id' => $kabid?->id,
                    'decision' => ApprovalDecision::Approved,
                    'notes' => 'Verifikasi kelayakan dan persyaratan medis telah sesuai.',
                    'decided_at' => $sample['submitted_at']->copy()->addHours(8),
                ]);

                Approval::create([
                    'approvable_type' => PbiReactivation::class,
                    'approvable_id' => $pbi->id,
                    'step' => ApprovalStep::Kadis,
                    'approver_id' => $kadis?->id,
                    'decision' => ApprovalDecision::Approved,
                    'notes' => 'Rekomendasi disetujui untuk diusulkan ke Kemensos RI.',
                    'decided_at' => $sample['submitted_at']->copy()->addHours(24),
                ]);
            }

            // Status histories
            $currentTime = $sample['submitted_at']->copy();
            foreach ($sample['history'] as $hist) {
                $currentTime = $currentTime->addHours(4);
                StatusHistory::create([
                    'statusable_type' => ServiceRequest::class,
                    'statusable_id' => $request->id,
                    'from_status' => $hist['from'],
                    'to_status' => $hist['to'],
                    'notes' => $hist['notes'],
                    'user_id' => $officer?->id,
                    'created_at' => $currentTime,
                ]);
            }
        }
    }
}
