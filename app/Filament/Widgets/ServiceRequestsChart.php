<?php

namespace App\Filament\Widgets;

use App\Models\ServiceType;
use Filament\Widgets\ChartWidget;

class ServiceRequestsChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Pengajuan per Jenis Layanan';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $types = ServiceType::withCount('serviceRequests')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pengajuan',
                    'data' => $types->pluck('service_requests_count')->toArray(),
                    'backgroundColor' => [
                        '#3b82f6', // DTSEN (Blue)
                        '#ef4444', // PBI (Red)
                        '#10b981', // REHSOS (Green)
                        '#f59e0b', // REK_BANSOS (Amber)
                    ],
                ],
            ],
            'labels' => $types->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
