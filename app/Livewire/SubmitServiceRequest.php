<?php

namespace App\Livewire;

use App\Enums\DocumentVerificationStatus;
use App\Enums\PbiReactivationReason;
use App\Enums\ServiceRequestStatus;
use App\Models\District;
use App\Models\DtsenCertificate;
use App\Models\DtsenPurpose;
use App\Models\NumberSequence;
use App\Models\PbiReactivation;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestDocument;
use App\Models\ServiceType;
use App\Models\StatusHistory;
use App\Models\Village;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class SubmitServiceRequest extends Component
{
    use WithFileUploads;

    public string $serviceCode = 'DTSEN';

    // Data Pemohon
    public string $applicant_name = '';

    public string $applicant_nik = '';

    public string $family_card_number = '';

    public string $phone = '';

    public string $address = '';

    public ?int $district_id = null;

    public ?int $village_id = null;

    // Field Khusus DTSEN
    public ?int $dtsen_purpose_id = null;

    public string $purpose_description = '';

    public string $subject_name = '';

    public string $subject_nik = '';

    public string $relationship_to_applicant = 'Anak Kandung';

    // Field Khusus PBI
    public string $participant_name = '';

    public string $participant_nik = '';

    public string $bpjs_card_number = '';

    public string $deactivated_date = '';

    public string $pbi_reason = 'emergency';

    public string $health_facility_name = '';

    public string $health_letter_number = '';

    public bool $is_emergency = false;

    // Upload Dokumen
    public array $uploads = [];

    public function mount(?string $type = null)
    {
        if ($type) {
            $this->serviceCode = strtoupper(trim($type));
        }

        $firstDistrict = District::first();
        if ($firstDistrict) {
            $this->district_id = $firstDistrict->id;
            $firstVillage = Village::where('district_id', $firstDistrict->id)->first();
            $this->village_id = $firstVillage?->id;
        }

        $firstPurpose = DtsenPurpose::where('is_active', true)->first();
        if ($firstPurpose) {
            $this->dtsen_purpose_id = $firstPurpose->id;
        }
    }

    public function updatedDistrictId($val)
    {
        $firstVillage = Village::where('district_id', $val)->first();
        $this->village_id = $firstVillage?->id;
    }

    public function changeService(string $code)
    {
        $this->serviceCode = $code;
        $this->resetValidation();
    }

    public function submit()
    {
        $this->validate([
            'applicant_name' => 'required|string|min:3|max:100',
            'applicant_nik' => 'required|digits:16',
            'family_card_number' => 'required|digits:16',
            'phone' => 'required|string|min:10|max:15',
            'address' => 'required|string|min:5',
            'village_id' => 'required|exists:villages,id',
        ], [
            'applicant_name.required' => 'Nama lengkap pemohon wajib diisi.',
            'applicant_nik.digits' => 'NIK pemohon harus tepat 16 digit angka.',
            'family_card_number.digits' => 'Nomor Kartu Keluarga harus tepat 16 digit angka.',
            'phone.required' => 'Nomor WhatsApp / HP wajib diisi untuk pemberitahuan status tiket.',
            'address.required' => 'Alamat domisili lengkap wajib diisi.',
            'village_id.required' => 'Desa / Kelurahan domisili wajib dipilih.',
        ]);

        $serviceType = ServiceType::where('code', $this->serviceCode)->firstOrFail();

        // Validasi khusus DTSEN
        if ($this->serviceCode === 'DTSEN') {
            $this->validate([
                'dtsen_purpose_id' => 'required|exists:dtsen_purposes,id',
                'subject_name' => 'required|string|min:3',
                'subject_nik' => 'required|digits:16',
                'relationship_to_applicant' => 'required|string',
            ]);
        }

        // Validasi khusus PBI
        if ($this->serviceCode === 'PBI') {
            $this->validate([
                'participant_name' => 'required|string|min:3',
                'participant_nik' => 'required|digits:16',
                'bpjs_card_number' => 'required|string|min:8',
                'pbi_reason' => 'required|string',
            ]);
        }

        // Generate Nomor Tiket Otomatis Unik
        $requestNumber = NumberSequence::generateNext($this->serviceCode);

        $isPriority = ($this->serviceCode === 'PBI' && ($this->is_emergency || $this->pbi_reason === 'emergency'));

        // Simpan Pengajuan
        $request = ServiceRequest::create([
            'request_number' => $requestNumber,
            'service_type_id' => $serviceType->id,
            'applicant_name' => $this->applicant_name,
            'applicant_nik' => $this->applicant_nik,
            'family_card_number' => $this->family_card_number,
            'address' => $this->address,
            'village_id' => $this->village_id,
            'phone' => $this->phone,
            'submitted_at' => now(),
            'status' => ServiceRequestStatus::Submitted,
            'is_priority' => $isPriority,
            'verification_result' => null,
            'officer_notes' => null,
        ]);

        // Simpan Detail DTSEN
        if ($this->serviceCode === 'DTSEN') {
            $purpose = DtsenPurpose::find($this->dtsen_purpose_id);
            DtsenCertificate::create([
                'service_request_id' => $request->id,
                'dtsen_purpose_id' => $this->dtsen_purpose_id,
                'purpose_description' => $this->purpose_description ?: ($purpose?->name ?? 'Permohonan Keterangan'),
                'subject_name' => $this->subject_name,
                'subject_nik' => $this->subject_nik,
                'relationship_to_applicant' => $this->relationship_to_applicant,
                'is_registered' => false,
                'decile' => null,
                'checked_at' => null,
                'verification_code' => 'DTSEN-'.strtoupper(Str::random(10)),
            ]);
        }

        // Simpan Detail PBI
        if ($this->serviceCode === 'PBI') {
            PbiReactivation::create([
                'service_request_id' => $request->id,
                'participant_name' => $this->participant_name,
                'participant_nik' => $this->participant_nik,
                'bpjs_card_number' => $this->bpjs_card_number,
                'deactivated_date' => $this->deactivated_date ?: null,
                'reason' => PbiReactivationReason::tryFrom($this->pbi_reason) ?? PbiReactivationReason::Other,
                'health_facility_name' => $this->health_facility_name,
                'health_letter_number' => $this->health_letter_number,
            ]);
        }

        // Simpan Dokumen
        foreach ($serviceType->requirements as $req) {
            $file = $this->uploads[$req->id] ?? null;
            $storedPath = null;
            $originalName = null;

            if ($file) {
                $storedPath = $file->store('documents/uploads', 'local');
                $originalName = $file->getClientOriginalName();
            } else {
                $storedPath = 'documents/sample_dummy.pdf';
                $originalName = $req->name.' - '.$this->applicant_name.'.pdf';
            }

            ServiceRequestDocument::create([
                'service_request_id' => $request->id,
                'service_requirement_id' => $req->id,
                'file_path' => $storedPath,
                'original_name' => $originalName,
                'verification_status' => DocumentVerificationStatus::Pending,
                'notes' => null,
            ]);
        }

        // Catat Riwayat Status Awal
        StatusHistory::create([
            'statusable_type' => ServiceRequest::class,
            'statusable_id' => $request->id,
            'from_status' => null,
            'to_status' => 'submitted',
            'notes' => 'Pengajuan berhasil dikirim secara mandiri oleh pemohon melalui portal SAPA SOSIAL.',
            'user_id' => null,
            'created_at' => now(),
        ]);

        session()->flash('success_ticket', $requestNumber);

        return redirect()->route('tracking', ['ticket' => $requestNumber]);
    }

    public function render()
    {
        $serviceTypes = ServiceType::where('is_active', true)->with('requirements')->get();
        $districts = District::with('villages')->orderBy('name')->get();
        $villages = Village::where('district_id', $this->district_id)->orderBy('name')->get();
        $dtsenPurposes = DtsenPurpose::where('is_active', true)->get();
        $currentType = ServiceType::where('code', $this->serviceCode)->with('requirements')->first();

        return view('livewire.submit-service-request', [
            'serviceTypes' => $serviceTypes,
            'districts' => $districts,
            'villages' => $villages,
            'dtsenPurposes' => $dtsenPurposes,
            'currentType' => $currentType,
        ])->layout('layouts.app', ['title' => 'Formulir Pengajuan Layanan Online — SAPA SOSIAL']);
    }
}
