<?php

namespace App\Livewire;

use App\Enums\ComplaintStatus;
use App\Enums\PublishStatus;
use App\Enums\ServiceRequestStatus;
use App\Models\Complaint;
use App\Models\InformationPage;
use App\Models\PbiReactivation;
use App\Models\RehabilitationCase;
use App\Models\ServiceRequest;
use App\Models\ServiceType;
use Livewire\Component;

class HomePage extends Component
{
    public string $ticketNumber = '';

    public function searchTicket()
    {
        $ticket = trim($this->ticketNumber);
        if (empty($ticket)) {
            return;
        }

        return redirect()->route('tracking', ['ticket' => $ticket]);
    }

    public function render()
    {
        $stats = [
            'completed_services' => ServiceRequest::where('status', ServiceRequestStatus::Completed)->count(),
            'reactivated_pbi' => PbiReactivation::whereNotNull('reactivated_date')->count(),
            'resolved_complaints' => Complaint::where('status', ComplaintStatus::Resolved)->count(),
            'rehsos_handled' => RehabilitationCase::count(),
        ];

        $serviceTypes = ServiceType::where('is_active', true)
            ->withCount('requirements')
            ->get();

        $latestArticles = InformationPage::where('publish_status', PublishStatus::Published)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('livewire.home-page', [
            'stats' => $stats,
            'serviceTypes' => $serviceTypes,
            'latestArticles' => $latestArticles,
        ])->layout('layouts.app', ['title' => 'SAPA SOSIAL — Satu Pintu Layanan Sosial Kabupaten Blitar']);
    }
}
