<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Title -->
        <div class="text-center max-w-xl mx-auto mb-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                Pemeriksaan Keabsahan
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Verifikasi Keaslian Dokumen</h1>
            <p class="mt-2 text-sm text-slate-600">
                Pindai kode QR pada surat atau masukkan kode verifikasi untuk memeriksa keaslian surat keterangan yang diterbitkan Dinas Sosial Kabupaten Blitar.
            </p>
        </div>

        <!-- Search / Verification Input Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-200/90 mb-10">
            <form wire:submit="verify" class="space-y-4">
                <div>
                    <label for="code" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Kode Verifikasi QR atau Nomor Surat Resmi
                    </label>
                    <div class="relative">
                        <input 
                            wire:model="code" 
                            id="code" 
                            type="text" 
                            placeholder="Contoh: DTSEN-6KNUXLQRAQ atau 400.9/012/409.105/2026" 
                            class="w-full pl-4 pr-12 py-3.5 rounded-xl border border-slate-300 text-slate-900 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent uppercase placeholder:normal-case shadow-xs"
                        >
                        <button type="submit" class="absolute inset-y-1.5 right-1.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-sm flex items-center gap-1.5 transition-colors">
                            <span>Verifikasi</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </button>
                    </div>
                </div>

                @if($searchError)
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{{ $searchError }}</span>
                    </div>
                @endif
            </form>
        </div>

        <!-- Verification Result: DTSEN Certificate -->
        @if($certificate)
            @php
                $isExpired = $certificate->valid_until && now()->gt($certificate->valid_until);
            @endphp
            <div class="bg-white rounded-3xl shadow-2xl border border-slate-200/90 overflow-hidden">
                <!-- Status Banner -->
                <div class="p-6 text-center {{ $isExpired ? 'bg-amber-600' : 'bg-emerald-600' }} text-white">
                    <div class="inline-flex p-3 rounded-full bg-white/20 mb-2">
                        @if($isExpired)
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        @else
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @endif
                    </div>
                    <h3 class="text-xl font-extrabold uppercase tracking-wider">
                        {{ $isExpired ? 'DOKUMEN KEDALUWARSA' : 'DOKUMEN RESMI & TERVERIFIKASI' }}
                    </h3>
                    <p class="text-xs text-white/90 mt-1">
                        {{ $isExpired ? 'Masa berlaku surat keterangan ini telah berakhir.' : 'Surat ini diterbitkan secara sah oleh Dinas Sosial Kabupaten Blitar.' }}
                    </p>
                </div>

                <!-- Certificate Metadata Details -->
                <div class="p-6 sm:p-8 space-y-6">
                    <div class="border-b border-slate-100 pb-4 text-center">
                        <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Nomor Surat Resmi</span>
                        <div class="text-xl sm:text-2xl font-black text-slate-900 font-mono tracking-tight mt-0.5">
                            {{ $certificate->certificate_number ?: 'Dalam Proses Penerbitan' }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-500 block">Nama Subjek:</span>
                            <strong class="text-slate-900 font-bold">{{ $certificate->subject_name }}</strong>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-500 block">NIK Subjek:</span>
                            <strong class="text-slate-900 font-bold font-mono">
                                {{ substr($certificate->subject_nik, 0, 6) . '******' . substr($certificate->subject_nik, -4) }}
                            </strong>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-500 block">Tujuan Penggunaan:</span>
                            <strong class="text-slate-900 font-bold">{{ $certificate->dtsenPurpose?->name ?? $certificate->purpose_description }}</strong>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-500 block">Status SIKS-NG / Desil:</span>
                            <strong class="text-emerald-700 font-bold">
                                {{ $certificate->is_registered ? 'Terdaftar (Desil ' . $certificate->decile . ')' : 'Tidak Terdaftar' }}
                            </strong>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-500 block">Tanggal Diterbitkan:</span>
                            <strong class="text-slate-900 font-bold">
                                {{ $certificate->issued_at ? $certificate->issued_at->translatedFormat('d F Y') : '-' }}
                            </strong>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-500 block">Masa Berlaku Hingga:</span>
                            <strong class="{{ $isExpired ? 'text-rose-600' : 'text-slate-900' }} font-bold">
                                {{ $certificate->valid_until ? date('d F Y', strtotime($certificate->valid_until)) : 'Tidak terbatas' }}
                            </strong>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100 text-xs text-slate-600 flex items-center justify-between">
                        <div>
                            <span class="text-slate-500 block">Pejabat Penandatangan:</span>
                            <strong class="text-slate-900 font-bold text-sm">{{ $certificate->signer?->name ?? 'Kepala Dinas Sosial Kabupaten Blitar' }}</strong>
                        </div>
                        <div class="text-right">
                            <span class="text-slate-500 block">Kode QR Verifikasi:</span>
                            <strong class="font-mono text-blue-900 font-extrabold">{{ $certificate->verification_code }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Verification Result: PBI Recommendation -->
        @if($pbiReactivation)
            <div class="bg-white rounded-3xl shadow-2xl border border-slate-200/90 overflow-hidden">
                <div class="p-6 text-center bg-blue-600 text-white">
                    <div class="inline-flex p-3 rounded-full bg-white/20 mb-2">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-extrabold uppercase tracking-wider">
                        REKOMENDASI RESMI DINAS SOSIAL
                    </h3>
                    <p class="text-xs text-white/90 mt-1">
                        Surat Rekomendasi Reaktivasi KIS / PBI-JK Kabupaten Blitar
                    </p>
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    <div class="border-b border-slate-100 pb-4 text-center">
                        <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Nomor Rekomendasi</span>
                        <div class="text-xl sm:text-2xl font-black text-slate-900 font-mono tracking-tight mt-0.5">
                            {{ $pbiReactivation->recommendation_number }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-500 block">Nama Peserta:</span>
                            <strong class="text-slate-900 font-bold">{{ $pbiReactivation->participant_name }}</strong>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-500 block">Nomor BPJS / KIS:</span>
                            <strong class="text-slate-900 font-bold font-mono">{{ $pbiReactivation->bpjs_card_number }}</strong>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-500 block">Alasan Pengusulan:</span>
                            <strong class="text-slate-900 font-bold">{{ $pbiReactivation->reason->label() }}</strong>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-500 block">Status Kepesertaan:</span>
                            <strong class="{{ $pbiReactivation->reactivated_date ? 'text-emerald-700' : 'text-blue-700' }} font-bold">
                                {{ $pbiReactivation->reactivated_date ? 'Sudah Aktif Kembali' : 'Dalam Proses Reaktivasi' }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
