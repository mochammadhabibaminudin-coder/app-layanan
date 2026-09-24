<?php

namespace App\Livewire;

use App\Models\DtsenCertificate;
use App\Models\PbiReactivation;
use Livewire\Component;

class VerifyDocument extends Component
{
    public string $code = '';

    public bool $isSearched = false;

    public ?string $searchError = null;

    public ?DtsenCertificate $certificate = null;

    public ?PbiReactivation $pbiReactivation = null;

    public function mount(?string $code = null)
    {
        if (! empty($code)) {
            $this->code = strtoupper(trim($code));
            $this->verify();
        }
    }

    public function verify()
    {
        $this->reset(['searchError', 'certificate', 'pbiReactivation']);
        $this->isSearched = true;

        $searchCode = strtoupper(trim($this->code));
        if (empty($searchCode)) {
            $this->searchError = 'Silakan masukkan kode verifikasi atau nomor surat.';

            return;
        }

        // 1. Cek Surat Keterangan DTSEN
        $cert = DtsenCertificate::with([
            'serviceRequest.village.district',
            'dtsenPurpose',
            'signer',
        ])->where('verification_code', $searchCode)
            ->orWhere('certificate_number', $searchCode)
            ->first();

        if ($cert) {
            $this->certificate = $cert;

            return;
        }

        // 2. Cek Surat Rekomendasi Reaktivasi PBI-JK
        $pbi = PbiReactivation::with([
            'serviceRequest.village.district',
            'signer',
        ])->where('recommendation_number', $searchCode)
            ->first();

        if ($pbi) {
            $this->pbiReactivation = $pbi;

            return;
        }

        $this->searchError = 'Kode verifikasi atau nomor surat "'.$searchCode.'" tidak ditemukan dalam basis data resmi Dinas Sosial Kabupaten Blitar.';
    }

    public function render()
    {
        return view('livewire.verify-document')
            ->layout('layouts.app', ['title' => 'Verifikasi Keaslian Dokumen — SAPA SOSIAL']);
    }
}
