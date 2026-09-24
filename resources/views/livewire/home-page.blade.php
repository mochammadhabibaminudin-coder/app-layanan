<div>
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-b from-blue-900 via-indigo-900 to-slate-900 text-white overflow-hidden py-20 lg:py-28">
        <!-- Background Pattern & Glow -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-xs font-semibold uppercase tracking-wider mb-6">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Portal Resmi Dinas Sosial Kabupaten Blitar
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white mb-6 leading-tight">
                    Satu Pintu Layanan Sosial <span class="bg-gradient-to-r from-blue-300 via-sky-200 to-indigo-200 bg-clip-text text-transparent">Kabupaten Blitar</span>
                </h1>

                <p class="text-lg sm:text-xl text-slate-300 mb-10 leading-relaxed font-normal">
                    Layanan digital terpadu untuk pengurusan SK DTSEN, reaktivasi KIS/PBI-JK nonaktif, rujukan rehabilitasi, dan pengaduan sosial secara transparan dan mudah dipantau.
                </p>

                <!-- Ticket Search Input on Hero -->
                <div class="bg-white/10 backdrop-blur-xl p-2 sm:p-2.5 rounded-2xl border border-white/20 shadow-2xl max-w-xl mx-auto mb-10">
                    <form wire:submit="searchTicket" class="flex flex-col sm:flex-row gap-2">
                        <div class="relative flex-grow">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                            <input 
                                wire:model="ticketNumber" 
                                type="text" 
                                placeholder="Masukkan Nomor Tiket (cth: DTSEN-202609-00001)..." 
                                class="w-full pl-10 pr-4 py-3.5 rounded-xl bg-white text-slate-900 placeholder-slate-400 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-inner"
                            >
                        </div>
                        <button 
                            type="submit" 
                            class="px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-600/30 transition-all hover:scale-[1.02] flex items-center justify-center gap-2"
                        >
                            <span>Lacak Tiket</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </button>
                    </form>
                </div>

                <!-- CTA Shortcuts -->
                <div class="flex flex-wrap items-center justify-center gap-4 text-sm font-semibold">
                    <a href="{{ route('service.apply') }}" class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white shadow-md transition-all hover:scale-105">
                        Ajukan Permohonan Layanan
                    </a>
                    <a href="{{ route('complaint.create') }}" class="px-5 py-3 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 text-slate-200 transition-all">
                        Pengaduan Masalah Sosial
                    </a>
                    <a href="{{ route('verify') }}" class="px-5 py-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 transition-all">
                        Cek Keaslian Surat
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Transparansi & Statistik Layanan -->
    <section class="relative -mt-10 z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-xl border border-slate-200/80 hover:border-blue-300 transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ number_format($stats['completed_services']) }}</div>
                        <div class="text-xs sm:text-sm font-medium text-slate-500">Layanan Selesai</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-xl border border-slate-200/80 hover:border-emerald-300 transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ number_format($stats['reactivated_pbi']) }}</div>
                        <div class="text-xs sm:text-sm font-medium text-slate-500">KIS/PBI Direaktivasi</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-xl border border-slate-200/80 hover:border-amber-300 transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ number_format($stats['rehsos_handled']) }}</div>
                        <div class="text-xs sm:text-sm font-medium text-slate-500">Kasus Rehsos Ditangani</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-xl border border-slate-200/80 hover:border-indigo-300 transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ number_format($stats['resolved_complaints']) }}</div>
                        <div class="text-xs sm:text-sm font-medium text-slate-500">Aduan Selesai</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan Prioritas Section -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-xs font-bold uppercase tracking-widest text-blue-600 mb-2">Pelayanan Utama</h2>
                <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900">3 Layanan Prioritas Masyarakat</h3>
                <p class="mt-3 text-slate-600 text-sm sm:text-base">
                    Akses pengurusan layanan prioritas Dinas Sosial Kabupaten Blitar secara online tanpa kendala birokrasi berbelit.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1: SK DTSEN -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                SLA 1 Hari Kerja
                            </span>
                        </div>

                        <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-blue-600 transition-colors">
                            Surat Keterangan DTSEN
                        </h4>
                        <p class="text-slate-600 text-sm leading-relaxed mb-6">
                            Surat keterangan resmi status peringkat desil di SIKS-NG untuk syarat SPMB jalur afirmasi, Program Indonesia Pintar (PIP), KIP Kuliah, dan bantuan sosial.
                        </p>

                        <div class="bg-slate-50 rounded-2xl p-4 mb-6 border border-slate-100 text-xs text-slate-600 space-y-1.5">
                            <div class="font-bold text-slate-700">Syarat Minimal:</div>
                            <div class="flex items-center gap-2">✓ Foto KTP Pemohon</div>
                            <div class="flex items-center gap-2">✓ Foto Kartu Keluarga (KK)</div>
                            <div class="flex items-center gap-2">✓ Surat Rekomendasi Sekolah (opsional)</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('service.apply', ['type' => 'DTSEN']) }}" class="flex-1 text-center py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md transition-colors">
                            Ajukan Sekarang
                        </a>
                        <a href="{{ route('information.show', ['slug' => 'surat-keterangan-dtsen']) }}" class="p-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors" title="Lihat SOP & Panduan">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </a>
                    </div>
                </div>

                <!-- Card 2: Reaktivasi PBI-JK -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-rose-600 text-white flex items-center justify-center shadow-lg shadow-rose-500/30 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                Prioritas Medis
                            </span>
                        </div>

                        <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-rose-600 transition-colors">
                            Reaktivasi KIS / PBI-JK
                        </h4>
                        <p class="text-slate-600 text-sm leading-relaxed mb-6">
                            Fasilitasi penerbitan rekomendasi dan pengusulan aktifasi kembali kepesertaan BPJS Kesehatan PBI-JK nonaktif ke Kemensos RI bagi warga tidak mampu.
                        </p>

                        <div class="bg-slate-50 rounded-2xl p-4 mb-6 border border-slate-100 text-xs text-slate-600 space-y-1.5">
                            <div class="font-bold text-slate-700">Kriteria Penerima:</div>
                            <div class="flex items-center gap-2">✓ Pasien Penyakit Kronis / Katastropik</div>
                            <div class="flex items-center gap-2">✓ Kondisi Kedaruratan Medis RS</div>
                            <div class="flex items-center gap-2">✓ Bayi baru lahir dari ibu peserta PBI</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('service.apply', ['type' => 'PBI']) }}" class="flex-1 text-center py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm shadow-md transition-colors">
                            Ajukan Reaktivasi
                        </a>
                        <a href="{{ route('information.show', ['slug' => 'reaktivasi-kis-pbi-jk']) }}" class="p-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors" title="Lihat SOP & Panduan">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </a>
                    </div>
                </div>

                <!-- Card 3: Rehabilitasi Sosial -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-amber-600 text-white flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                Rehsos & Rujukan
                            </span>
                        </div>

                        <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-amber-600 transition-colors">
                            Rehabilitasi Sosial
                        </h4>
                        <p class="text-slate-600 text-sm leading-relaxed mb-6">
                            Penanganan terpadu Pemerlu Pelayanan Kesejahteraan Sosial (PPKS): lansia terlantar, disabilitas, ODGJ terlantar, anak, serta rujukan panti dan rumah sakit.
                        </p>

                        <div class="bg-slate-50 rounded-2xl p-4 mb-6 border border-slate-100 text-xs text-slate-600 space-y-1.5">
                            <div class="font-bold text-slate-700">Fasilitas Penanganan:</div>
                            <div class="flex items-center gap-2">✓ Assessment & Bantuan Alat Bantu</div>
                            <div class="flex items-center gap-2">✓ Rujukan UPTD PSTW (Panti Lansia)</div>
                            <div class="flex items-center gap-2">✓ Evakuasi Medis ODGJ ke RS Jiwa</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('complaint.create') }}" class="flex-1 text-center py-2.5 px-4 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm shadow-md transition-colors">
                            Laporkan Klien PPKS
                        </a>
                        <a href="{{ route('information.show', ['slug' => 'pelayanan-rehabilitasi-sosial']) }}" class="p-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors" title="Lihat SOP & Panduan">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Alur Pelayanan 5 Langkah -->
    <section class="py-20 bg-white border-y border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-xs font-bold uppercase tracking-widest text-blue-600 mb-2">Transparansi Proses</h2>
                <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Alur Layanan Mudah & Terlacak</h3>
                <p class="mt-3 text-slate-600 text-sm sm:text-base">
                    Setiap tahapan pengajuan Anda tercatat rapi di dalam sistem dan dapat dicek status perkembangannya secara mandiri.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 relative">
                <div class="bg-slate-50 rounded-2xl p-6 text-center border border-slate-200/60 relative">
                    <div class="w-12 h-12 rounded-xl bg-blue-600 text-white font-bold text-lg flex items-center justify-center mx-auto mb-4 shadow-md shadow-blue-500/20">1</div>
                    <h4 class="font-bold text-slate-900 mb-2 text-base">Pilih Layanan</h4>
                    <p class="text-xs text-slate-600">Pilih jenis layanan, isi formulir data diri & unggah berkas KTP/KK.</p>
                </div>

                <div class="bg-slate-50 rounded-2xl p-6 text-center border border-slate-200/60 relative">
                    <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white font-bold text-lg flex items-center justify-center mx-auto mb-4 shadow-md shadow-indigo-500/20">2</div>
                    <h4 class="font-bold text-slate-900 mb-2 text-base">Nomor Tiket</h4>
                    <p class="text-xs text-slate-600">Sistem otomatis menerbitkan nomor tiket unik untuk pelacakan berkas.</p>
                </div>

                <div class="bg-slate-50 rounded-2xl p-6 text-center border border-slate-200/60 relative">
                    <div class="w-12 h-12 rounded-xl bg-sky-600 text-white font-bold text-lg flex items-center justify-center mx-auto mb-4 shadow-md shadow-sky-500/20">3</div>
                    <h4 class="font-bold text-slate-900 mb-2 text-base">Verifikasi</h4>
                    <p class="text-xs text-slate-600">Petugas memeriksa berkas dan melakukan pengecekan data di SIKS-NG.</p>
                </div>

                <div class="bg-slate-50 rounded-2xl p-6 text-center border border-slate-200/60 relative">
                    <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white font-bold text-lg flex items-center justify-center mx-auto mb-4 shadow-md shadow-emerald-500/20">4</div>
                    <h4 class="font-bold text-slate-900 mb-2 text-base">Persetujuan</h4>
                    <p class="text-xs text-slate-600">Draf diverifikasi Kepala Bidang dan disetujui Kepala Dinas Sosial.</p>
                </div>

                <div class="bg-slate-50 rounded-2xl p-6 text-center border border-slate-200/60 relative">
                    <div class="w-12 h-12 rounded-xl bg-teal-600 text-white font-bold text-lg flex items-center justify-center mx-auto mb-4 shadow-md shadow-teal-500/20">5</div>
                    <h4 class="font-bold text-slate-900 mb-2 text-base">Terbit QR</h4>
                    <p class="text-xs text-slate-600">Surat resmi terbit dengan kode verifikasi QR Code & siap diunduh.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Informasi & Panduan Terbaru -->
    @if($latestArticles->count() > 0)
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-12 gap-4">
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-widest text-blue-600 mb-2">Pusat Informasi</h2>
                    <h3 class="text-3xl font-extrabold text-slate-900">Panduan & Informasi Layanan</h3>
                </div>
                <a href="{{ route('information.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">
                    Lihat Semua Panduan & FAQ
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($latestArticles as $article)
                <article class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-md hover:shadow-xl transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700">
                                {{ $article->category->label() }}
                            </span>
                            <span class="text-xs text-slate-400">
                                {{ $article->published_at?->translatedFormat('d M Y') }}
                            </span>
                        </div>

                        <h4 class="font-bold text-slate-900 text-lg mb-2 line-clamp-2 hover:text-blue-600 transition-colors">
                            <a href="{{ route('information.show', ['slug' => $article->slug]) }}">
                                {{ $article->title }}
                            </a>
                        </h4>

                        <p class="text-sm text-slate-600 line-clamp-3 mb-6">
                            {{ $article->description }}
                        </p>
                    </div>

                    <a href="{{ route('information.show', ['slug' => $article->slug]) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-800 pt-4 border-t border-slate-100">
                        Baca Selengkapnya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
