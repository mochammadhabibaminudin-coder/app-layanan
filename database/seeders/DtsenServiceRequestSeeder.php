<?php

namespace Database\Seeders;

use App\Enums\ApprovalDecision;
use App\Enums\ApprovalStep;
use App\Enums\DocumentVerificationStatus;
use App\Enums\ServiceRequestStatus;
use App\Models\Approval;
use App\Models\DtsenCertificate;
use App\Models\DtsenPurpose;
use App\Models\NumberSequence;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestDocument;
use App\Models\ServiceType;
use App\Models\StatusHistory;
use App\Models\User;
use App\Models\Village;
use App\Models\WorkUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DtsenServiceRequestSeeder extends Seeder
{
    public function run(): void
    {
        $dtsenType = ServiceType::where('code', 'DTSEN')->first();
        if (! $dtsenType) {
            return;
        }

        $workUnit = WorkUnit::where('name', 'like', '%Linjamsos%')->first();
        $officer = User::where('email', 'petugas.pelayanan@dinsos.blitarkab.go.id')->first();
        $kabid = User::where('email', 'kabid.linjamsos@dinsos.blitarkab.go.id')->first();
        $kadis = User::where('email', 'kadis@dinsos.blitarkab.go.id')->first();

        $kanigoro = Village::where('name', 'Kanigoro')->first();
        $garum = Village::where('name', 'Garum')->first();
        $wlingi = Village::where('name', 'Wlingi')->first();
        $srengat = Village::where('name', 'Srengat')->first();
        $sutojayan = Village::where('name', 'Sutojayan')->first();

        $purposeSpmb = DtsenPurpose::where('code', 'spmb')->first();
        $purposePip = DtsenPurpose::where('code', 'pip')->first();
        $purposeKip = DtsenPurpose::where('code', 'kip_kuliah')->first();
        $purposeBansos = DtsenPurpose::where('code', 'bansos')->first();

        $requirements = $dtsenType->requirements;

        // Data sampel pengajuan DTSEN
        $samples = [
            [
                'status' => ServiceRequestStatus::Completed,
                'applicant_name' => 'Supriyanto',
                'applicant_nik' => '3505011204820001',
                'family_card_number' => '3505012501090001',
                'address' => 'Jl. Kenanga No. 14, RT 02 RW 03',
                'village_id' => $kanigoro?->id,
                'phone' => '081234567901',
                'submitted_at' => now()->subDays(5),
                'completed_at' => now()->subDays(3),
                'purpose' => $purposeSpmb,
                'purpose_desc' => 'Pendaftaran SPMB SMA Jalur Afirmasi',
                'subject_name' => 'Dimas Arya Pratama',
                'subject_nik' => '3505010507080002',
                'relationship' => 'Anak Kandung',
                'is_registered' => true,
                'decile' => 2,
                'cert_number' => '400.9/012/409.105/2026',
                'issued_at' => now()->subDays(3),
                'valid_until' => now()->addDays(87),
                'history' => [
                    ['from' => null, 'to' => 'submitted', 'notes' => 'Pengajuan berhasil dikirimkan oleh pemohon.'],
                    ['from' => 'submitted', 'to' => 'document_check', 'notes' => 'Pemeriksaan berkas KTP dan KK lengkap.'],
                    ['from' => 'document_check', 'to' => 'data_verification', 'notes' => 'Pengecekan data di SIKS-NG: Terdaftar Desil 2.'],
                    ['from' => 'data_verification', 'to' => 'awaiting_approval', 'notes' => 'Draf surat keterangan dibuat, diajukan paraf Kabid.'],
                    ['from' => 'awaiting_approval', 'to' => 'issued', 'notes' => 'Surat ditandatangani Kepala Dinas dan diterbitkan.'],
                    ['from' => 'issued', 'to' => 'completed', 'notes' => 'Surat telah diunduh oleh pemohon.'],
                ],
                'approved' => true,
            ],
            [
                'status' => ServiceRequestStatus::AwaitingApproval,
                'applicant_name' => 'Siti Nurhaliza',
                'applicant_nik' => '3505024508850003',
                'family_card_number' => '3505021803120002',
                'address' => 'Dusun Bence RT 01 RW 02',
                'village_id' => $garum?->id,
                'phone' => '081333444555',
                'submitted_at' => now()->subDays(2),
                'completed_at' => null,
                'purpose' => $purposePip,
                'purpose_desc' => 'Kelengkapan usulan PIP jenjang SMP',
                'subject_name' => 'Anisa Rahmawati',
                'subject_nik' => '3505026210110001',
                'relationship' => 'Anak Kandung',
                'is_registered' => true,
                'decile' => 3,
                'cert_number' => null,
                'issued_at' => null,
                'valid_until' => null,
                'history' => [
                    ['from' => null, 'to' => 'submitted', 'notes' => 'Pengajuan diajukan oleh pemohon.'],
                    ['from' => 'submitted', 'to' => 'document_check', 'notes' => 'Berkas persyaratan valid.'],
                    ['from' => 'document_check', 'to' => 'data_verification', 'notes' => 'Cek SIKS-NG: Terdaftar Desil 3.'],
                    ['from' => 'data_verification', 'to' => 'awaiting_approval', 'notes' => 'Menunggu persetujuan / tanda tangan.'],
                ],
                'approved' => false,
            ],
            [
                'status' => ServiceRequestStatus::DataVerification,
                'applicant_name' => 'Bambang Sutrisno',
                'applicant_nik' => '3505041002780004',
                'family_card_number' => '3505040101050003',
                'address' => 'Kelurahan Beru RT 04 RW 01',
                'village_id' => $wlingi?->id,
                'phone' => '082155667788',
                'submitted_at' => now()->subDay(),
                'completed_at' => null,
                'purpose' => $purposeKip,
                'purpose_desc' => 'Pendaftaran KIP Kuliah di Perguruan Tinggi',
                'subject_name' => 'Rizky Kurniawan',
                'subject_nik' => '3505041508060002',
                'relationship' => 'Anak Kandung',
                'is_registered' => true,
                'decile' => 1,
                'cert_number' => null,
                'issued_at' => null,
                'valid_until' => null,
                'history' => [
                    ['from' => null, 'to' => 'submitted', 'notes' => 'Pengajuan diajukan online.'],
                    ['from' => 'submitted', 'to' => 'document_check', 'notes' => 'Berkas KTP dan KK sesuai.'],
                    ['from' => 'document_check', 'to' => 'data_verification', 'notes' => 'Sedang dalam pengecekan petugas SIKS-NG.'],
                ],
                'approved' => false,
            ],
            [
                'status' => ServiceRequestStatus::RevisionRequested,
                'applicant_name' => 'Endang Sulastri',
                'applicant_nik' => '3505055206890002',
                'family_card_number' => '3505052204100004',
                'address' => 'Jl. Kauman No. 8',
                'village_id' => $srengat?->id,
                'phone' => '085799887766',
                'submitted_at' => now()->subHours(18),
                'completed_at' => null,
                'purpose' => $purposeSpmb,
                'purpose_desc' => 'SPMB Jalur Afirmasi SMP',
                'subject_name' => 'Fajar Nugroho',
                'subject_nik' => '3505051909120001',
                'relationship' => 'Anak Kandung',
                'is_registered' => false,
                'decile' => null,
                'cert_number' => null,
                'issued_at' => null,
                'valid_until' => null,
                'history' => [
                    ['from' => null, 'to' => 'submitted', 'notes' => 'Pengajuan terkirim.'],
                    ['from' => 'submitted', 'to' => 'revision_requested', 'notes' => 'Foto Kartu Keluarga (KK) buram dan tidak terbaca. Mohon unggah ulang foto KK yang jelas.'],
                ],
                'approved' => false,
            ],
            [
                'status' => ServiceRequestStatus::Rejected,
                'applicant_name' => 'Joko Purwanto',
                'applicant_nik' => '3505030803800005',
                'family_card_number' => '3505031405080005',
                'address' => 'Desa Kalipang RT 03 RW 02',
                'village_id' => $sutojayan?->id,
                'phone' => '087812345678',
                'submitted_at' => now()->subDays(6),
                'completed_at' => now()->subDays(5),
                'purpose' => $purposeBansos,
                'purpose_desc' => 'Permohonan Bantuan Sosial Reguler',
                'subject_name' => 'Joko Purwanto',
                'subject_nik' => '3505030803800005',
                'relationship' => 'Diri Sendiri',
                'is_registered' => true,
                'decile' => 7,
                'cert_number' => null,
                'issued_at' => null,
                'valid_until' => null,
                'history' => [
                    ['from' => null, 'to' => 'submitted', 'notes' => 'Pengajuan dikirim pemohon.'],
                    ['from' => 'submitted', 'to' => 'document_check', 'notes' => 'Berkas lengkap.'],
                    ['from' => 'document_check', 'to' => 'data_verification', 'notes' => 'Pengecekan SIKS-NG: Terdaftar di Desil 7.'],
                    ['from' => 'data_verification', 'to' => 'rejected', 'notes' => 'Pengajuan ditolak karena hasil pengecekan SIKS-NG menunjukkan Desil 7, melebihi batas ketentuan tujuan bansos (Maksimal Desil 3).'],
                ],
                'approved' => false,
            ],
        ];

        foreach ($samples as $sample) {
            $reqNumber = NumberSequence::generateNext('DTSEN', $sample['submitted_at']);

            $request = ServiceRequest::create([
                'request_number' => $reqNumber,
                'service_type_id' => $dtsenType->id,
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
                'is_priority' => false,
                'verification_result' => $sample['is_registered'] ? ('Terdaftar di SIKS-NG Desil '.$sample['decile']) : 'Belum terverifikasi',
                'officer_notes' => $sample['status'] === ServiceRequestStatus::Rejected ? 'Desil di luar kriteria' : 'Berkas memenuhi syarat',
                'service_result' => $sample['cert_number'] ? ('Diterbitkan SK DTSEN No: '.$sample['cert_number']) : null,
                'rejection_reason' => $sample['status'] === ServiceRequestStatus::Rejected ? 'Hasil cek SIKS-NG menunjukkan Desil 7 (melebihi batas maksimal desil 3)' : null,
                'completed_at' => $sample['completed_at'],
            ]);

            // Buat dokumen pengajuan
            foreach ($requirements as $req) {
                ServiceRequestDocument::create([
                    'service_request_id' => $request->id,
                    'service_requirement_id' => $req->id,
                    'file_path' => 'documents/dtsen/'.Str::slug($req->name).'_sample.pdf',
                    'original_name' => $req->name.' - '.$sample['applicant_name'].'.pdf',
                    'verification_status' => $sample['status'] === ServiceRequestStatus::RevisionRequested && str_contains($req->name, 'KK')
                        ? DocumentVerificationStatus::RevisionNeeded
                        : DocumentVerificationStatus::Valid,
                    'notes' => $sample['status'] === ServiceRequestStatus::RevisionRequested && str_contains($req->name, 'KK')
                        ? 'Gambar/scan buram'
                        : 'Sesuai',
                ]);
            }

            // Buat detail SK DTSEN
            $verCode = 'DTSEN-'.strtoupper(Str::random(10));
            $certificate = DtsenCertificate::create([
                'service_request_id' => $request->id,
                'dtsen_purpose_id' => $sample['purpose']->id ?? 1,
                'purpose_description' => $sample['purpose_desc'],
                'subject_name' => $sample['subject_name'],
                'subject_nik' => $sample['subject_nik'],
                'relationship_to_applicant' => $sample['relationship'],
                'is_registered' => $sample['is_registered'],
                'decile' => $sample['decile'],
                'checked_at' => $sample['submitted_at']->copy()->addHours(3),
                'checker_id' => $officer?->id,
                'certificate_number' => $sample['cert_number'],
                'issued_at' => $sample['issued_at'],
                'valid_until' => $sample['valid_until'],
                'signer_id' => $sample['cert_number'] ? $kadis?->id : null,
                'file_path' => $sample['cert_number'] ? 'certificates/dtsen/'.Str::slug($reqNumber).'.pdf' : null,
                'verification_code' => $verCode,
            ]);

            // Buat data approvals bila relevan
            if ($sample['approved']) {
                Approval::create([
                    'approvable_type' => DtsenCertificate::class,
                    'approvable_id' => $certificate->id,
                    'step' => ApprovalStep::Kabid,
                    'approver_id' => $kabid?->id,
                    'decision' => ApprovalDecision::Approved,
                    'notes' => 'Telah diperiksa, data sesuai dengan database SIKS-NG.',
                    'decided_at' => $sample['submitted_at']->copy()->addHours(6),
                ]);

                Approval::create([
                    'approvable_type' => DtsenCertificate::class,
                    'approvable_id' => $certificate->id,
                    'step' => ApprovalStep::Kadis,
                    'approver_id' => $kadis?->id,
                    'decision' => ApprovalDecision::Approved,
                    'notes' => 'Disetujui untuk diterbitkan surat keterangan.',
                    'decided_at' => $sample['submitted_at']->copy()->addHours(12),
                ]);
            }

            // Status histories
            $currentTime = $sample['submitted_at']->copy();
            foreach ($sample['history'] as $hist) {
                $currentTime = $currentTime->addHours(2);
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
