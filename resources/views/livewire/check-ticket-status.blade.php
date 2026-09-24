<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header Title -->
        <div class="text-center max-w-xl mx-auto mb-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-bold mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                Pelacakan Real-time
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Cek Status & Lacak Tiket</h1>
            <p class="mt-2 text-sm text-slate-600">
                Pantau progres verifikasi permohonan layanan atau pengaduan sosial Anda menggunakan nomor tiket resmi.
            </p>
        </div>

        <!-- Success Flash message after applying -->
        @if (session()->has('success_ticket'))
            <div class="mb-8 p-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 shadow-md">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-base text-emerald-950">Permohonan Berhasil Dikirim!</h4>
                        <p class="text-sm text-emerald-800 mt-1">
                            Nomor Tiket Anda adalah <strong class="font-extrabold tracking-wider">{{ session('success_ticket') }}</strong>. Simpan nomor tiket ini untuk memantau status atau mencetak surat keterangan saat terbit.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if (session()->has('success_complaint'))
            <div class="mb-8 p-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 shadow-md">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-base text-emerald-950">Pengaduan Berhasil Diterima!</h4>
                        <p class="text-sm text-emerald-800 mt-1">
                            Nomor Laporan Anda adalah <strong class="font-extrabold tracking-wider">{{ session('success_complaint') }}</strong>. Petugas kami akan segera menindaklanjuti laporan Anda.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Search Form Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-200/90 mb-10">
            <form wire:submit="search" class="space-y-4">
                <div>
                    <label for="ticket" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Nomor Tiket / Pengaduan
                    </label>
                    <div class="relative">
                        <input 
                            wire:model="ticket" 
                            id="ticket" 
                            type="text" 
                            placeholder="Contoh: DTSEN-202609-00001 atau ADU-202609-00001" 
                            class="w-full pl-4 pr-12 py-3.5 rounded-xl border border-slate-300 text-slate-900 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent uppercase placeholder:normal-case shadow-xs"
                        >
                        <button type="submit" class="absolute inset-y-1.5 right-1.5 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-sm flex items-center gap-1.5 transition-colors">
                            <span>Cari</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </button>
                    </div>
                </div>

                @if($searchError)
                    <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{{ $searchError }}</span>
                    </div>
                @endif
            </form>
        </div>

        <!-- Result Container: Service Request -->
        @if($serviceRequest)
            <div class="bg-white rounded-3xl shadow-xl border border-slate-200/90 overflow-hidden mb-8">
                <!-- Status Header Banner -->
                <div class="bg-gradient-to-r from-blue-900 to-indigo-900 text-white p-6 sm:p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs uppercase tracking-wider font-semibold text-blue-200">Pengajuan Layanan</span>
                            <span class="text-xs px-2.5 py-0.5 rounded-full bg-white/20 font-bold">{{ $serviceRequest->serviceType->name }}</span>
                            @if($serviceRequest->is_priority)
                                <span class="text-xs px-2.5 py-0.5 rounded-full bg-rose-500 font-bold animate-pulse">DARURAT MEDIS</span>
                            @endif
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-wide font-mono">{{ $serviceRequest->request_number }}</h2>
                        <p class="text-xs text-blue-200 mt-1">Diajukan pada: {{ $serviceRequest->submitted_at?->translatedFormat('d F Y, H:i') }} WIB</p>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-extrabold bg-white text-blue-950 shadow-md">
                            Status: {{ $serviceRequest->status->label() }}
                        </span>
                    </div>
                </div>

                <!-- Privacy Verification Box (If not yet verified) -->
                @if(!$isVerified)
                    <div class="p-6 sm:p-8 bg-amber-50/70 border-b border-amber-200">
                        <div class="max-w-md mx-auto text-center">
                            <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center mx-auto mb-3 shadow-md shadow-amber-500/20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <h3 class="font-bold text-slate-900 text-base mb-1">Verifikasi Keamanan Privasi</h3>
                            <p class="text-xs text-slate-600 mb-4">
                                Masukkan <strong>4 Digit Terakhir NIK</strong> atau <strong>4 Digit Terakhir No. HP</strong> pemohon untuk membuka rincian lengkap dan mengunduh berkas.
                            </p>
                            <form wire:submit="verifyAccess" class="flex gap-2">
                                <input 
                                    wire:model="verificationDigits" 
                                    type="password" 
                                    maxlength="4" 
                                    placeholder="4 Digit Terakhir..." 
                                    class="flex-1 text-center py-2.5 px-4 rounded-xl border border-slate-300 text-slate-900 text-sm font-bold focus:ring-2 focus:ring-blue-600 focus:outline-none"
                                >
                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-bold text-sm">
                                    Buka Detail
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <div class="p-6 sm:p-8 space-y-8">
                    <!-- Progress Stepper Timeline -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-6">Perjalanan & Riwayat Status Tiket</h4>
                        <div class="relative pl-6 sm:pl-8 border-l-2 border-blue-200 space-y-6">
                            @foreach($serviceRequest->statusHistories as $hist)
                                <div class="relative group">
                                    <div class="absolute -left-[31px] sm:-left-[39px] top-0 w-4 h-4 rounded-full bg-blue-600 border-4 border-white shadow-xs"></div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold px-2 py-0.5 rounded bg-blue-50 text-blue-800">
                                                {{ $hist->to_status }}
                                            </span>
                                            <span class="text-xs text-slate-400">
                                                {{ $hist->created_at->translatedFormat('d M Y, H:i') }} WIB
                                            </span>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-800 mt-1">{{ $hist->notes }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Verified Details -->
                    @if($isVerified)
                        <div class="pt-6 border-t border-slate-200 space-y-6">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Rincian Data Pengajuan</h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                                    <span class="text-xs text-slate-500 block">Nama Pemohon:</span>
                                    <strong class="text-slate-900 text-base font-bold">{{ $serviceRequest->applicant_name }}</strong>
                                </div>
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                                    <span class="text-xs text-slate-500 block">NIK Pemohon:</span>
                                    <strong class="text-slate-900 text-base font-bold font-mono">
                                        {{ substr($serviceRequest->applicant_nik, 0, 6) . '******' . substr($serviceRequest->applicant_nik, -4) }}
                                    </strong>
                                </div>
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                                    <span class="text-xs text-slate-500 block">Wilayah Domisili:</span>
                                    <strong class="text-slate-900 font-bold">
                                        {{ $serviceRequest->village?->name }}, Kec. {{ $serviceRequest->village?->district?->name }}
                                    </strong>
                                </div>
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                                    <span class="text-xs text-slate-500 block">Hasil Verifikasi Petugas:</span>
                                    <strong class="text-slate-900 font-bold">
                                        {{ $serviceRequest->verification_result ?: 'Sedang dalam proses verifikasi' }}
                                    </strong>
                                </div>
                            </div>

                            <!-- Detail Khusus SK DTSEN -->
                            @if($serviceRequest->dtsenCertificate)
                                @php $cert = $serviceRequest->dtsenCertificate; @endphp
                                <div class="p-6 rounded-2xl bg-blue-50/70 border border-blue-200 space-y-4">
                                    <div class="flex items-center justify-between">
                                        <h5 class="font-bold text-blue-950 text-base">Surat Keterangan DTSEN</h5>
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $cert->certificate_number ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $cert->certificate_number ? 'SURAT DITERBITKAN' : 'MENUNGGU PENGESAHAN' }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                        <div><span class="text-slate-500">Tujuan Penggunaan:</span> <div class="font-bold text-slate-800">{{ $cert->dtsenPurpose?->name ?? $cert->purpose_description }}</div></div>
                                        <div><span class="text-slate-500">Orang yang Diterangkan:</span> <div class="font-bold text-slate-800">{{ $cert->subject_name }} ({{ $cert->relationship_to_applicant }})</div></div>
                                        <div><span class="text-slate-500">Status SIKS-NG:</span> <div class="font-bold text-slate-800">{{ $cert->is_registered ? 'Terdaftar di Desil ' . $cert->decile : 'Belum terverifikasi' }}</div></div>
                                        <div><span class="text-slate-500">Nomor Surat:</span> <div class="font-bold text-slate-800 font-mono">{{ $cert->certificate_number ?: 'Belum terbit' }}</div></div>
                                    </div>

                                    @if($cert->certificate_number)
                                        <div class="pt-4 border-t border-blue-200/80 flex flex-wrap items-center justify-between gap-4">
                                            <div class="text-xs text-slate-600">
                                                Kode Verifikasi: <strong class="font-mono text-blue-900 font-extrabold">{{ $cert->verification_code }}</strong>
                                            </div>
                                            <a href="{{ route('verify', ['code' => $cert->verification_code]) }}" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition-colors">
                                                Lihat Bukti Pengesahan & Validasi QR
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Detail Khusus Reaktivasi PBI -->
                            @if($serviceRequest->pbiReactivation)
                                @php $pbi = $serviceRequest->pbiReactivation; @endphp
                                <div class="p-6 rounded-2xl bg-rose-50/70 border border-rose-200 space-y-4">
                                    <div class="flex items-center justify-between">
                                        <h5 class="font-bold text-rose-950 text-base">Rekomendasi Reaktivasi KIS / PBI-JK</h5>
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $pbi->reactivated_date ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ $pbi->reactivated_date ? 'KEPESERTAAN SUDAH AKTIF' : 'PROSES REAKTIVASI' }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                        <div><span class="text-slate-500">Nama Peserta:</span> <div class="font-bold text-slate-800">{{ $pbi->participant_name }}</div></div>
                                        <div><span class="text-slate-500">Nomor Kartu BPJS:</span> <div class="font-bold text-slate-800 font-mono">{{ $pbi->bpjs_card_number }}</div></div>
                                        <div><span class="text-slate-500">Alasan Reaktivasi:</span> <div class="font-bold text-slate-800">{{ $pbi->reason->label() }}</div></div>
                                        <div><span class="text-slate-500">Surat Rekomendasi:</span> <div class="font-bold text-slate-800 font-mono">{{ $pbi->recommendation_number ?: 'Dalam proses telaah' }}</div></div>
                                        <div><span class="text-slate-500">Respon Kemensos:</span> <div class="font-bold text-slate-800">{{ $pbi->ministry_decision?->label() ?? 'Menunggu proses pusat' }}</div></div>
                                        <div><span class="text-slate-500">Tanggal Aktif Kembali:</span> <div class="font-bold text-emerald-700">{{ $pbi->reactivated_date ? date('d M Y', strtotime($pbi->reactivated_date)) : 'Menunggu update BPJS' }}</div></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Result Container: Complaint -->
        @if($complaint)
            <div class="bg-white rounded-3xl shadow-xl border border-slate-200/90 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-indigo-900 to-slate-900 text-white p-6 sm:p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <span class="text-xs uppercase tracking-wider font-semibold text-indigo-200">Laporan Pengaduan Sosial</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-wide font-mono mt-1">{{ $complaint->complaint_number }}</h2>
                        <p class="text-xs text-indigo-200 mt-1">Kategori: {{ $complaint->complaintCategory->name }}</p>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-extrabold bg-white text-indigo-950 shadow-md">
                            Status: {{ $complaint->status->label() }}
                        </span>
                    </div>
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 text-sm space-y-3">
                        <div>
                            <span class="text-xs text-slate-500 block">Lokasi Kejadian:</span>
                            <strong class="text-slate-800 font-bold">{{ $complaint->location_detail }}, {{ $complaint->village?->name }}, Kec. {{ $complaint->village?->district?->name }}</strong>
                        </div>
                        <div>
                            <span class="text-xs text-slate-500 block">Deskripsi Laporan:</span>
                            <p class="text-slate-700 mt-0.5">{{ $complaint->description }}</p>
                        </div>
                        @if($complaint->action_taken)
                            <div class="pt-3 border-t border-slate-200">
                                <span class="text-xs text-emerald-700 font-bold block">Tindakan Penanganan yang Dilakukan:</span>
                                <p class="text-slate-800 font-semibold mt-0.5">{{ $complaint->action_taken }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Riwayat Penanganan -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4">Riwayat Penanganan Laporan</h4>
                        <div class="relative pl-6 border-l-2 border-indigo-200 space-y-4">
                            @foreach($complaint->statusHistories as $h)
                                <div class="relative">
                                    <div class="absolute -left-[31px] top-0 w-3.5 h-3.5 rounded-full bg-indigo-600 border-2 border-white"></div>
                                    <div class="text-xs text-slate-400">{{ $h->created_at->translatedFormat('d M Y, H:i') }} WIB</div>
                                    <div class="text-sm font-bold text-slate-800">{{ $h->to_status }}</div>
                                    <div class="text-xs text-slate-600 mt-0.5">{{ $h->notes }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
