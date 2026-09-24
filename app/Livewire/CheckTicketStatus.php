<?php

namespace App\Livewire;

use App\Models\Complaint;
use App\Models\ServiceRequest;
use Livewire\Component;

class CheckTicketStatus extends Component
{
    public string $ticket = '';

    public string $verificationDigits = '';

    public bool $isSearched = false;

    public bool $isVerified = false;

    public ?string $searchError = null;

    public ?ServiceRequest $serviceRequest = null;

    public ?Complaint $complaint = null;

    public function mount()
    {
        $ticketParam = request()->query('ticket');
        if (! empty($ticketParam)) {
            $this->ticket = strtoupper(trim($ticketParam));
            $this->search();
        }
    }

    public function search()
    {
        $this->reset(['searchError', 'serviceRequest', 'complaint', 'isVerified']);
        $this->isSearched = true;

        $ticket = strtoupper(trim($this->ticket));
        if (empty($ticket)) {
            $this->searchError = 'Silakan masukkan nomor tiket pengajuan atau pengaduan.';

            return;
        }

        // Cari di Layanan Pengajuan (DTSEN, PBI, Bansos)
        $req = ServiceRequest::with([
            'serviceType',
            'village.district',
            'documents.serviceRequirement',
            'statusHistories' => fn ($q) => $q->latest('created_at'),
            'dtsenCertificate.signer',
            'pbiReactivation.signer',
        ])->where('request_number', $ticket)->first();

        if ($req) {
            $this->serviceRequest = $req;
            $this->verifyAccess();

            return;
        }

        // Cari di Pengaduan Sosial
        $comp = Complaint::with([
            'complaintCategory',
            'village.district',
            'attachments',
            'statusHistories' => fn ($q) => $q->latest('created_at'),
            'dispositions.toWorkUnit',
        ])->where('complaint_number', $ticket)->first();

        if ($comp) {
            $this->complaint = $comp;
            $this->verifyAccess();

            return;
        }

        $this->searchError = 'Nomor tiket "'.$ticket.'" tidak ditemukan. Pastikan format nomor sudah benar (contoh: DTSEN-202609-00001, PBI-202609-00001, atau ADU-202609-00001).';
    }

    public function verifyAccess()
    {
        $digits = trim($this->verificationDigits);

        if (empty($digits)) {
            $this->isVerified = false;

            return;
        }

        if ($this->serviceRequest) {
            $nikEnd = substr(trim((string) $this->serviceRequest->applicant_nik), -4);
            $phoneEnd = substr(trim((string) $this->serviceRequest->phone), -4);

            if ($digits === $nikEnd || $digits === $phoneEnd) {
                $this->isVerified = true;
                $this->searchError = null;
            } else {
                $this->searchError = '4 Digit verifikasi tidak cocok dengan data NIK atau Nomor HP pemohon.';
            }
        } elseif ($this->complaint) {
            $phoneEnd = substr(trim((string) $this->complaint->reporter_phone), -4);

            if ($digits === $phoneEnd) {
                $this->isVerified = true;
                $this->searchError = null;
            } else {
                $this->searchError = '4 Digit verifikasi tidak cocok dengan Nomor HP pelapor.';
            }
        }
    }

    public function render()
    {
        return view('livewire.check-ticket-status')
            ->layout('layouts.app', ['title' => 'Cek Status Tiket & Lacak Layanan — SAPA SOSIAL']);
    }
}
