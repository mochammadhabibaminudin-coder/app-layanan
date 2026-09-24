<?php

namespace Database\Seeders;

use App\Enums\DocumentVerificationStatus;
use App\Enums\ServiceRequestStatus;
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

class GeneralServiceRequestSeeder extends Seeder
{
    public function run(): void
    {
        $bansosType = ServiceType::where('code', 'REK_BANSOS')->first();
        if (! $bansosType) {
            return;
        }

        if (ServiceRequest::where('service_type_id', $bansosType->id)->where('applicant_nik', '3505054101700001')->exists()) {
            return;
        }

        $workUnit = WorkUnit::where('name', 'like', '%Dayasos%')->first();
        $officer = User::where('email', 'petugas.pelayanan@dinsos.blitarkab.go.id')->first();
        $srengat = Village::where('name', 'Srengat')->first();
        $kanigoro = Village::where('name', 'Kanigoro')->first();

        $requirements = $bansosType->requirements;

        // 1. Pengajuan Rekomendasi Bansos - Selesai
        $req1Number = NumberSequence::generateNext('REK', now()->subDays(7));
        $request1 = ServiceRequest::create([
            'request_number' => $req1Number,
            'service_type_id' => $bansosType->id,
            'submitter_id' => null,
            'applicant_name' => 'Wartini',
            'applicant_nik' => '3505054101700001',
            'family_card_number' => '3505051205080007',
            'address' => 'Kelurahan Dandong RT 01 RW 02',
            'village_id' => $srengat?->id ?? 1,
            'phone' => '081298765432',
            'submitted_at' => now()->subDays(7),
            'officer_id' => $officer?->id,
            'work_unit_id' => $workUnit?->id,
            'status' => ServiceRequestStatus::Completed,
            'is_priority' => false,
            'verification_result' => 'Verifikasi berkas dan kondisi lapangan memenuhi kriteria keluarga rentan.',
            'officer_notes' => 'Telah dilakukan tinjauan rumah oleh petugas desa dan TKSK.',
            'assessment_notes' => 'Kondisi rumah dinding bambu, lantai tanah, penghasilan tidak menentu sebagai buruh tani.',
            'service_result' => 'Surat Rekomendasi Bantuan Sosial Nomor: 460/088/409.105/2026 telah diserahkan.',
            'rejection_reason' => null,
            'completed_at' => now()->subDays(2),
        ]);

        foreach ($requirements as $req) {
            ServiceRequestDocument::create([
                'service_request_id' => $request1->id,
                'service_requirement_id' => $req->id,
                'file_path' => 'documents/general/'.Str::slug($req->name).'_sample.pdf',
                'original_name' => $req->name.' - Wartini.pdf',
                'verification_status' => DocumentVerificationStatus::Valid,
                'notes' => 'Terverifikasi absah',
            ]);
        }

        StatusHistory::create([
            'statusable_type' => ServiceRequest::class,
            'statusable_id' => $request1->id,
            'from_status' => null,
            'to_status' => 'submitted',
            'notes' => 'Pengajuan diajukan oleh pemohon melalui operator kecamatan.',
            'user_id' => $officer?->id,
            'created_at' => now()->subDays(7),
        ]);
        StatusHistory::create([
            'statusable_type' => ServiceRequest::class,
            'statusable_id' => $request1->id,
            'from_status' => 'submitted',
            'to_status' => 'verification',
            'notes' => 'Pemeriksaan berkas dan data DTKS/DTSEN.',
            'user_id' => $officer?->id,
            'created_at' => now()->subDays(6),
        ]);
        StatusHistory::create([
            'statusable_type' => ServiceRequest::class,
            'statusable_id' => $request1->id,
            'from_status' => 'verification',
            'to_status' => 'assessment',
            'notes' => 'Verifikasi lapangan oleh pendamping sosial.',
            'user_id' => $officer?->id,
            'created_at' => now()->subDays(4),
        ]);
        StatusHistory::create([
            'statusable_type' => ServiceRequest::class,
            'statusable_id' => $request1->id,
            'from_status' => 'assessment',
            'to_status' => 'completed',
            'notes' => 'Surat rekomendasi terbit dan diserahkan ke pemohon.',
            'user_id' => $officer?->id,
            'created_at' => now()->subDays(2),
        ]);

        // 2. Pengajuan Rekomendasi Bansos - In Process
        $req2Number = NumberSequence::generateNext('REK', now()->subDays(2));
        $request2 = ServiceRequest::create([
            'request_number' => $req2Number,
            'service_type_id' => $bansosType->id,
            'submitter_id' => null,
            'applicant_name' => 'Slamet Riyadi',
            'applicant_nik' => '3505011506820002',
            'family_card_number' => '3505011908050004',
            'address' => 'Desa Tlogo RT 02 RW 01',
            'village_id' => $kanigoro?->id ?? 1,
            'phone' => '085611223399',
            'submitted_at' => now()->subDays(2),
            'officer_id' => $officer?->id,
            'work_unit_id' => $workUnit?->id,
            'status' => ServiceRequestStatus::InProcess,
            'is_priority' => false,
            'verification_result' => 'Berkas lengkap, sedang dalam penjadwalan assessment lapangan.',
            'officer_notes' => 'Menunggu konfirmasi jadwal TKSK Kecamatan Kanigoro.',
            'assessment_notes' => null,
            'service_result' => null,
            'rejection_reason' => null,
            'completed_at' => null,
        ]);

        foreach ($requirements as $req) {
            ServiceRequestDocument::create([
                'service_request_id' => $request2->id,
                'service_requirement_id' => $req->id,
                'file_path' => 'documents/general/'.Str::slug($req->name).'_sample.pdf',
                'original_name' => $req->name.' - Slamet.pdf',
                'verification_status' => DocumentVerificationStatus::Valid,
                'notes' => 'Berkas sesuai',
            ]);
        }
    }
}
