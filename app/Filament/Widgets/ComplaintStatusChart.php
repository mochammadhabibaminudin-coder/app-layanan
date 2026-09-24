<?php

namespace App\Filament\Widgets;

use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use Filament\Widgets\ChartWidget;

class ComplaintStatusChart extends ChartWidget
{
    protected ?string $heading = 'Status Penanganan Pengaduan Masyarakat';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $statuses = [
            ComplaintStatus::Received,
            ComplaintStatus::Verification,
            ComplaintStatus::Dispatched,
            ComplaintStatus::InHandling,
            ComplaintStatus::Resolved,
            ComplaintStatus::Duplicate,
        ];

        $counts = [];
        $labels = [];

        foreach ($statuses as $status) {
            $labels[] = $status->label();
            $counts[] = Complaint::where('status', $status)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Laporan',
                    'data' => $counts,
                    'backgroundColor' => [
                        '#64748b', // Received
                        '#0ea5e9', // Verification
                        '#f59e0b', // Dispatched
                        '#8b5cf6', // InHandling
                        '#10b981', // Resolved
                        '#94a3b8', // Duplicate
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
