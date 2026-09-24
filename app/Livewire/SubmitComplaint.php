<?php

namespace App\Livewire;

use App\Enums\ComplaintAttachmentType;
use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use App\Models\ComplaintCategory;
use App\Models\District;
use App\Models\NumberSequence;
use App\Models\StatusHistory;
use App\Models\Village;
use Livewire\Component;
use Livewire\WithFileUploads;

class SubmitComplaint extends Component
{
    use WithFileUploads;

    public string $reporter_name = '';

    public string $reporter_phone = '';

    public ?int $complaint_category_id = null;

    public ?int $district_id = null;

    public ?int $village_id = null;

    public string $location_detail = '';

    public string $description = '';

    public $attachment = null;

    public function mount()
    {
        $firstCategory = ComplaintCategory::where('is_active', true)->first();
        if ($firstCategory) {
            $this->complaint_category_id = $firstCategory->id;
        }

        $firstDistrict = District::first();
        if ($firstDistrict) {
            $this->district_id = $firstDistrict->id;
            $firstVillage = Village::where('district_id', $firstDistrict->id)->first();
            $this->village_id = $firstVillage?->id;
        }
    }

    public function updatedDistrictId($val)
    {
        $firstVillage = Village::where('district_id', $val)->first();
        $this->village_id = $firstVillage?->id;
    }

    public function submit()
    {
        $this->validate([
            'reporter_name' => 'required|string|min:3|max:100',
            'reporter_phone' => 'required|string|min:10|max:15',
            'complaint_category_id' => 'required|exists:complaint_categories,id',
            'village_id' => 'required|exists:villages,id',
            'location_detail' => 'required|string|min:5',
            'description' => 'required|string|min:15',
        ], [
            'reporter_name.required' => 'Nama lengkap pelapor wajib diisi.',
            'reporter_phone.required' => 'Nomor WhatsApp / HP aktif wajib diisi.',
            'complaint_category_id.required' => 'Pilih kategori permasalahan sosial.',
            'village_id.required' => 'Pilih desa/kelurahan lokasi kejadian.',
            'location_detail.required' => 'Tuliskan patokan / alamat jelas lokasi kejadian.',
            'description.required' => 'Jelaskan kondisi atau permasalahan sosial yang dilaporkan.',
            'description.min' => 'Deskripsi laporan minimal 15 karakter.',
        ]);

        $complaintNumber = NumberSequence::generateNext('ADU');

        $complaint = Complaint::create([
            'complaint_number' => $complaintNumber,
            'complaint_category_id' => $this->complaint_category_id,
            'reporter_id' => null,
            'reporter_name' => $this->reporter_name,
            'reporter_phone' => $this->reporter_phone,
            'location_detail' => $this->location_detail,
            'village_id' => $this->village_id,
            'description' => $this->description,
            'reported_at' => now(),
            'status' => ComplaintStatus::Received,
        ]);

        if ($this->attachment) {
            $path = $this->attachment->store('complaints/uploads', 'local');
            ComplaintAttachment::create([
                'complaint_id' => $complaint->id,
                'file_path' => $path,
                'type' => ComplaintAttachmentType::Photo,
            ]);
        }

        StatusHistory::create([
            'statusable_type' => Complaint::class,
            'statusable_id' => $complaint->id,
            'from_status' => null,
            'to_status' => 'received',
            'notes' => 'Pengaduan berhasil masuk melalui portal pengaduan sosial SAPA SOSIAL.',
            'user_id' => null,
            'created_at' => now(),
        ]);

        session()->flash('success_complaint', $complaintNumber);

        return redirect()->route('tracking', ['ticket' => $complaintNumber]);
    }

    public function render()
    {
        $categories = ComplaintCategory::where('is_active', true)->get();
        $districts = District::with('villages')->orderBy('name')->get();
        $villages = Village::where('district_id', $this->district_id)->orderBy('name')->get();

        return view('livewire.submit-complaint', [
            'categories' => $categories,
            'districts' => $districts,
            'villages' => $villages,
        ])->layout('layouts.app', ['title' => 'Pengaduan & Laporan Masalah Sosial — SAPA SOSIAL']);
    }
}
