<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SAPA SOSIAL — Satu Pintu Layanan Sosial Terpadu Dinas Sosial Kabupaten Blitar. Layanan SK DTSEN, Reaktivasi KIS/PBI-JK, Rehabilitasi Sosial, dan Pengaduan Masyarakat.">
    <title>{{ $title ?? 'SAPA SOSIAL — Satu Pintu Layanan Sosial Kabupaten Blitar' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col selection:bg-blue-600 selection:text-white">

    <!-- Top Emergency & Info Banner -->
    <div class="bg-slate-900 text-slate-300 text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
            <div class="flex items-center gap-2 text-center sm:text-left">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                    KEDARURATAN SOSIAL 24 JAM
                </span>
                <span>Call Center TRC Dinsos Blitar: <strong class="text-white">0811-2233-4455</strong></span>
            </div>
            <div class="flex items-center gap-4 text-slate-400">
                <span>Jam Layanan Kantor: <strong>08.00 - 15.00 WIB</strong></span>
                <span class="hidden md:inline">|</span>
                <a href="{{ url('/admin/login') }}" class="hidden md:inline-flex items-center gap-1 text-slate-300 hover:text-white transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                    Portal Petugas
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header x-data="{ mobileMenuOpen: false }" class="bg-white/95 backdrop-blur-md sticky top-0 z-50 border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Brand / Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3.5 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-700 via-blue-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-blue-900 to-indigo-800 bg-clip-text text-transparent">SAPA SOSIAL</span>
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">KAB. BLITAR</span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium tracking-wide">Satu Pintu Layanan Sosial Terpadu</p>
                    </div>
                </a>

                <!-- Desktop Navigation Menu -->
                <nav class="hidden lg:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('home') ? 'text-blue-700 bg-blue-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        Beranda
                    </a>
                    <a href="{{ route('service.apply') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('service.apply') ? 'text-blue-700 bg-blue-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        Ajukan Layanan
                    </a>
                    <a href="{{ route('tracking') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('tracking') ? 'text-blue-700 bg-blue-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        Cek Status Tiket
                    </a>
                    <a href="{{ route('complaint.create') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('complaint.create') ? 'text-blue-700 bg-blue-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        Pengaduan
                    </a>
                    <a href="{{ route('information.index') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('information.*') ? 'text-blue-700 bg-blue-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        Panduan & FAQ
                    </a>
                    <a href="{{ route('verify') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('verify') ? 'text-blue-700 bg-blue-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        Verifikasi Surat
                    </a>
                </nav>

                <!-- Action Button & Login -->
                <div class="hidden lg:flex items-center gap-3">
                    <a href="{{ route('tracking') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-blue-600 text-blue-700 text-sm font-bold hover:bg-blue-50 transition-colors shadow-xs">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Lacak Tiket
                    </a>
                    <a href="{{ url('/admin/login') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-blue-700 to-indigo-600 hover:from-blue-800 hover:to-indigo-700 text-white text-sm font-bold shadow-md shadow-blue-500/20 transition-all hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Login Petugas
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center lg:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!mobileMenuOpen">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="mobileMenuOpen" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div x-show="mobileMenuOpen" x-transition class="lg:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-6 space-y-2">
            <a href="{{ route('home') }}" class="block px-3.5 py-2.5 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-50">Beranda</a>
            <a href="{{ route('service.apply') }}" class="block px-3.5 py-2.5 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-50">Ajukan Layanan</a>
            <a href="{{ route('tracking') }}" class="block px-3.5 py-2.5 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-50">Cek Status Tiket</a>
            <a href="{{ route('complaint.create') }}" class="block px-3.5 py-2.5 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-50">Pengaduan Sosial</a>
            <a href="{{ route('information.index') }}" class="block px-3.5 py-2.5 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-50">Panduan & FAQ</a>
            <a href="{{ route('verify') }}" class="block px-3.5 py-2.5 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-50">Verifikasi Surat Resmi</a>
            <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
                <a href="{{ url('/admin/login') }}" class="w-full text-center px-4 py-2.5 rounded-xl bg-blue-700 text-white font-bold">
                    Login Petugas / Back-Office
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-400 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Column 1: Identity -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-extrabold text-lg">
                            S
                        </div>
                        <div>
                            <h3 class="text-white font-extrabold text-lg">SAPA SOSIAL</h3>
                            <p class="text-xs text-slate-400">Dinas Sosial Kab. Blitar</p>
                        </div>
                    </div>
                    <p class="text-sm leading-relaxed text-slate-400">
                        Sistem digital terpadu pelayanan sosial masyarakat Kabupaten Blitar. Transparan, terpercaya, dan dapat dipantau langsung dari mana saja.
                    </p>
                </div>

                <!-- Column 2: 3 Layanan Prioritas -->
                <div>
                    <h4 class="text-white font-bold text-sm tracking-wider uppercase mb-4">Layanan Prioritas</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('service.apply', ['type' => 'DTSEN']) }}" class="hover:text-white transition-colors">Surat Keterangan DTSEN (SPMB/PIP/KIP)</a></li>
                        <li><a href="{{ route('service.apply', ['type' => 'PBI']) }}" class="hover:text-white transition-colors">Reaktivasi KIS / PBI-JK Nonaktif</a></li>
                        <li><a href="{{ route('service.apply', ['type' => 'REHSOS']) }}" class="hover:text-white transition-colors">Pelayanan Rehabilitasi Sosial</a></li>
                        <li><a href="{{ route('service.apply', ['type' => 'REK_BANSOS']) }}" class="hover:text-white transition-colors">Rekomendasi Bantuan Sosial</a></li>
                    </ul>
                </div>

                <!-- Column 3: Akses Cepat -->
                <div>
                    <h4 class="text-white font-bold text-sm tracking-wider uppercase mb-4">Layanan Masyarakat</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('tracking') }}" class="hover:text-white transition-colors">Lacak Tiket Layanan</a></li>
                        <li><a href="{{ route('complaint.create') }}" class="hover:text-white transition-colors">Kirim Pengaduan & Laporan Warga</a></li>
                        <li><a href="{{ route('verify') }}" class="hover:text-white transition-colors">Cek Keaslian Surat Keterangan</a></li>
                        <li><a href="{{ route('information.index') }}" class="hover:text-white transition-colors">Persyaratan & Unduh Formulir</a></li>
                    </ul>
                </div>

                <!-- Column 4: Kontak & Alamat -->
                <div>
                    <h4 class="text-white font-bold text-sm tracking-wider uppercase mb-4">Dinas Sosial Kab. Blitar</h4>
                    <address class="not-italic text-sm space-y-2 text-slate-400">
                        <p class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-blue-400 mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <span>Jl. Raya Kanigoro No. 12, Kec. Kanigoro, Kabupaten Blitar, Jawa Timur 66171</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            <span>(0342) 801234 / 0812-3456-7890</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            <span>dinsos@blitarkab.go.id</span>
                        </p>
                    </address>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-slate-900 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} Dinas Sosial Kabupaten Blitar. Hak Cipta Dilindungi Undang-Undang.</p>
                <div class="flex items-center gap-6">
                    <span>Versi Sistem: 1.0 (Laravel 13 & Livewire v4)</span>
                    <a href="{{ url('/admin') }}" class="hover:text-slate-400 transition-colors">Admin Panel</a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
