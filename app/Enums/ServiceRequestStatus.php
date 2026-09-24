<?php

namespace App\Enums;

enum ServiceRequestStatus: string
{
    case Submitted = 'submitted';
    case DocumentCheck = 'document_check';
    case RevisionRequested = 'revision_requested';
    case DataVerification = 'data_verification';
    case EligibilityVerification = 'eligibility_verification';
    case Verification = 'verification';
    case Assessment = 'assessment';
    case AwaitingApproval = 'awaiting_approval';
    case RecommendationIssued = 'recommendation_issued';
    case ProposedToMinistry = 'proposed_to_ministry';
    case MinistryApproved = 'ministry_approved';
    case MinistryRejected = 'ministry_rejected';
    case Reactivated = 'reactivated';
    case InProcess = 'in_process';
    case Issued = 'issued';
    case Completed = 'completed';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Submitted => 'Menunggu Pemeriksaan',
            self::DocumentCheck => 'Pemeriksaan Berkas',
            self::RevisionRequested => 'Perlu Perbaikan Berkas',
            self::DataVerification => 'Pengecekan Data (SIKS-NG)',
            self::EligibilityVerification => 'Verifikasi Kelayakan',
            self::Verification => 'Verifikasi Petugas',
            self::Assessment => 'Assessment Lapangan',
            self::AwaitingApproval => 'Menunggu Persetujuan',
            self::RecommendationIssued => 'Rekomendasi Terbit',
            self::ProposedToMinistry => 'Diusulkan ke Kemensos',
            self::MinistryApproved => 'Disetujui Kemensos',
            self::MinistryRejected => 'Ditolak Kemensos',
            self::Reactivated => 'Kepesertaan Aktif Kembali',
            self::InProcess => 'Sedang Diproses',
            self::Issued => 'Surat Keterangan Terbit',
            self::Completed => 'Selesai',
            self::Rejected => 'Ditolak',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Submitted => 'gray',
            self::DocumentCheck, self::DataVerification, self::EligibilityVerification, self::Verification, self::Assessment => 'info',
            self::RevisionRequested => 'warning',
            self::AwaitingApproval, self::InProcess, self::ProposedToMinistry => 'primary',
            self::RecommendationIssued, self::MinistryApproved, self::Issued => 'success',
            self::Reactivated, self::Completed => 'success',
            self::MinistryRejected, self::Rejected => 'danger',
        };
    }
}
