<?php

namespace App\Filament\Widgets;

use App\Enums\ComplaintStatus;
use App\Enums\RehabilitationCaseStatus;
use App\Enums\ServiceRequestStatus;
use App\Models\Complaint;
use App\Models\DtsenCertificate;
use App\Models\PbiReactivation;
use App\Models\RehabilitationCase;
use App\Models\ServiceRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $dtsenIssuedCount = DtsenCertificate::whereNotNull('certificate_number')->count();
        $dtsenPendingCount = ServiceRequest::whereHas('serviceType', fn ($q) => $q->where('code', 'DTSEN'))
            ->where('status', ServiceRequestStatus::AwaitingApproval)
            ->count();

        $pbiReactivatedCount = PbiReactivation::whereNotNull('reactivated_date')->count();
        $pbiEmergencyCount = ServiceRequest::whereHas('serviceType', fn ($q) => $q->where('code', 'PBI'))
            ->where('is_priority', true)
            ->whereNotIn('status', [ServiceRequestStatus::Completed, ServiceRequestStatus::Rejected])
            ->count();

        $rehsosActiveCount = RehabilitationCase::whereNotIn('status', [RehabilitationCaseStatus::Closed])->count();
        $complaintsPendingCount = Complaint::whereIn('status', [
            ComplaintStatus::Received,
            ComplaintStatus::Verification,
            ComplaintStatus::Dispatched,
            ComplaintStatus::InHandling,
        ])->count();

        return [
            Stat::make('SK DTSEN Diterbitkan', $dtsenIssuedCount)
                ->description($dtsenPendingCount.' menunggu tanda tangan pejabat')
                ->descriptionIcon('heroicon-m-document-check')
                ->color('success'),

            Stat::make('Reaktivasi PBI-JK Aktif', $pbiReactivatedCount)
                ->description($pbiEmergencyCount > 0 ? "{$pbiEmergencyCount} pengajuan darurat medis" : 'Semua prioritas tertangani')
                ->descriptionIcon($pbiEmergencyCount > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($pbiEmergencyCount > 0 ? 'danger' : 'primary'),

            Stat::make('Kasus Rehsos Aktif', $rehsosActiveCount)
                ->description('Dalam penanganan & rujukan')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),

            Stat::make('Pengaduan Berjalan', $complaintsPendingCount)
                ->description('Menunggu verifikasi / dalam tindak lanjut')
                ->descriptionIcon('heroicon-m-chat-bubble-bottom-center-text')
                ->color('info'),
        ];
    }
}
