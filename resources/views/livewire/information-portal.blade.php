<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($activeArticle)
            <!-- Detail Mode -->
            <div class="max-w-4xl mx-auto">
                <a href="{{ route('information.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 mb-6 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali ke Daftar Panduan & Informasi
                </a>

                <div class="bg-white rounded-3xl shadow-xl border border-slate-200/90 overflow-hidden mb-10">
                    <div class="p-8 sm:p-12 border-b border-slate-100">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs font-bold px-3 py-1 rounded-full bg-blue-100 text-blue-800">
                                {{ $activeArticle->category->label() }}
                            </span>
                            <span class="text-xs text-slate-400">
                                Diperbarui: {{ $activeArticle->published_at?->translatedFormat('d F Y') }}
                            </span>
                        </div>
                        <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight mb-4">
                            {{ $activeArticle->title }}
                        </h1>
                        <p class="text-base sm:text-lg text-slate-600 leading-relaxed">
                            {{ $activeArticle->description }}
                        </p>
                    </div>

                    <div class="p-8 sm:p-12 space-y-10">
                        <!-- Persyaratan Berkas -->
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2 mb-4">
                                <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-bold">1</span>
                                Persyaratan Berkas Dokumen
                            </h3>
                            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200/70 whitespace-pre-line text-sm text-slate-700 leading-relaxed">
                                {{ $activeArticle->requirements ?: 'Tidak ada dokumen khusus yang dipersyaratkan.' }}
                            </div>
                        </div>

                        <!-- Prosedur / Alur Pelayanan -->
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2 mb-4">
                                <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-bold">2</span>
                                Prosedur & Alur Pengurusan
                            </h3>
                            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200/70 whitespace-pre-line text-sm text-slate-700 leading-relaxed">
                                {{ $activeArticle->procedure ?: 'Hubungi petugas pelayanan kami untuk tata cara lengkap.' }}
                            </div>
                        </div>

                        <!-- Lokasi, Jam & Kontak -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="p-5 rounded-2xl bg-blue-50/70 border border-blue-100">
                                <span class="text-xs font-bold uppercase text-blue-800 block mb-1">Jam Pelayanan</span>
                                <p class="text-xs text-slate-700">{{ $activeArticle->service_hours ?: 'Senin - Jumat: 08.00 - 15.00 WIB' }}</p>
                            </div>
                            <div class="p-5 rounded-2xl bg-indigo-50/70 border border-indigo-100">
                                <span class="text-xs font-bold uppercase text-indigo-800 block mb-1">Lokasi Loket</span>
                                <p class="text-xs text-slate-700">{{ $activeArticle->location ?: 'Dinas Sosial Kabupaten Blitar' }}</p>
                            </div>
                            <div class="p-5 rounded-2xl bg-emerald-50/70 border border-emerald-100">
                                <span class="text-xs font-bold uppercase text-emerald-800 block mb-1">Kontak Pengelola</span>
                                <p class="text-xs text-slate-700">{{ $activeArticle->contact ?: '(0342) 801234' }}</p>
                            </div>
                        </div>

                        <!-- Formulir Unduhan -->
                        @if($activeArticle->downloadableForms->count() > 0)
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    Unduh Formulir & Dokumen Pendukung
                                </h3>
                                <div class="space-y-3">
                                    @foreach($activeArticle->downloadableForms as $form)
                                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xs">PDF</div>
                                                <div>
                                                    <h5 class="text-sm font-bold text-slate-900">{{ $form->name }}</h5>
                                                    <span class="text-xs text-slate-500">Versi {{ $form->version }}</span>
                                                </div>
                                            </div>
                                            <a href="#" class="px-3.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition-colors">
                                                Unduh File
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- FAQ Akordion -->
                        @if($activeArticle->faqs->count() > 0)
                            <div class="pt-6 border-t border-slate-200">
                                <h3 class="text-lg font-bold text-slate-900 mb-6">Pertanyaan yang Sering Diajukan (FAQ)</h3>
                                <div class="space-y-4">
                                    @foreach($activeArticle->faqs as $faq)
                                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                            <h5 class="text-sm font-bold text-slate-900 mb-2 flex items-start gap-2">
                                                <span class="text-blue-600 font-extrabold">Q:</span>
                                                <span>{{ $faq->question }}</span>
                                            </h5>
                                            <p class="text-xs sm:text-sm text-slate-600 pl-5 leading-relaxed">
                                                {{ $faq->answer }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- CTA Button to apply -->
                        <div class="p-6 rounded-2xl bg-gradient-to-r from-blue-700 to-indigo-700 text-white flex flex-col sm:flex-row items-center justify-between gap-4 shadow-lg shadow-blue-500/20">
                            <div>
                                <h4 class="font-extrabold text-lg">Sudah Menyiapkan Berkas Persyaratan?</h4>
                                <p class="text-xs text-blue-100 mt-0.5">Ajukan permohonan secara online sekarang tanpa perlu datang ke kantor.</p>
                            </div>
                            <a href="{{ route('service.apply', ['type' => $activeArticle->serviceType?->code ?? 'DTSEN']) }}" class="px-6 py-3 rounded-xl bg-white text-blue-900 font-extrabold text-sm hover:bg-blue-50 transition-colors shadow-md shrink-0">
                                Ajukan Permohonan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- List Mode -->
            <div class="text-center max-w-2xl mx-auto mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-bold mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    Pusat Informasi & SOP
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Panduan & Informasi Layanan Sosial</h1>
                <p class="mt-2 text-sm sm:text-base text-slate-600">
                    Pelajari persyaratan resmi, prosedur pengurusan, serta unduh formulir permohonan layanan Dinas Sosial Kabupaten Blitar.
                </p>
            </div>

            <!-- Search and Filter Bar -->
            <div class="bg-white rounded-3xl p-6 shadow-xl border border-slate-200/90 mb-10 space-y-4">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-grow">
                        <input 
                            wire:model.live.debounce.300ms="search" 
                            type="text" 
                            placeholder="Cari layanan, syarat, atau kata kunci (cth: SPMB, PIP, KIS)..." 
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 text-slate-900 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-blue-600"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                    </div>
                </div>

                <!-- Category Filters -->
                <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-100 text-xs font-semibold">
                    <button 
                        wire:click="filterCategory('all')" 
                        class="px-3.5 py-1.5 rounded-lg transition-colors {{ $category === 'all' ? 'bg-blue-600 text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Semua Kategori
                    </button>
                    @foreach($categories as $cat)
                        <button 
                            wire:click="filterCategory('{{ $cat->value }}')" 
                            class="px-3.5 py-1.5 rounded-lg transition-colors {{ $category === $cat->value ? 'bg-blue-600 text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                        >
                            {{ $cat->label() }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Articles Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($articles as $art)
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col justify-between hover:-translate-y-1">
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700">
                                    {{ $art->category->label() }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    {{ $art->published_at?->translatedFormat('d M Y') }}
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-slate-900 mb-2 leading-snug hover:text-blue-600 transition-colors">
                                <a href="{{ route('information.show', ['slug' => $art->slug]) }}">
                                    {{ $art->title }}
                                </a>
                            </h3>

                            <p class="text-xs sm:text-sm text-slate-600 line-clamp-3 mb-6">
                                {{ $art->description }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-400">
                                {{ $art->faqs->count() }} FAQ • {{ $art->downloadableForms->count() }} Formulir
                            </span>
                            <a href="{{ route('information.show', ['slug' => $art->slug]) }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-800">
                                Baca Panduan
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-500">
                        <p class="text-base font-semibold">Tidak ada panduan atau informasi yang cocok dengan pencarian Anda.</p>
                        <button wire:click="$set('search', '')" class="mt-2 text-sm text-blue-600 font-bold hover:underline">
                            Hapus Kata Kunci Pencarian
                        </button>
                    </div>
                @endforelse
            </div>
        @endif

    </div>
</div>
