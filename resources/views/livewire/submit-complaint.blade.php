<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Title Header -->
        <div class="text-center max-w-xl mx-auto mb-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-100 text-rose-800 text-xs font-bold mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                Layanan Warga
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pengaduan & Laporan Masalah Sosial</h1>
            <p class="mt-2 text-sm text-slate-600">
                Sampaikan laporan bila Anda menemukan warga lansia terlantar, ODGJ, penyandang disabilitas rentan, atau kendala penyaluran bantuan sosial di lingkungan Anda.
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-xl border border-slate-200/90">
            <form wire:submit="submit" class="space-y-6">

                <!-- Data Pelapor -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap Pelapor *</label>
                        <input wire:model="reporter_name" type="text" placeholder="Masukkan nama Anda..." class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-rose-600 focus:outline-none">
                        @error('reporter_name') <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor WhatsApp / HP Aktif *</label>
                        <input wire:model="reporter_phone" type="text" placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-rose-600 focus:outline-none">
                        <span class="text-[11px] text-slate-400 mt-1 block">Petugas akan menghubungi nomor ini jika butuh info tambahan.</span>
                        @error('reporter_phone') <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Kategori Pengaduan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Masalah Sosial *</label>
                    <select wire:model="complaint_category_id" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-rose-600 focus:outline-none text-sm">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('complaint_category_id') <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Lokasi Kejadian -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kecamatan Lokasi Kejadian *</label>
                        <select wire:model.live="district_id" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-rose-600 focus:outline-none">
                            @foreach($districts as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Desa / Kelurahan Lokasi Kejadian *</label>
                        <select wire:model="village_id" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-rose-600 focus:outline-none">
                            @foreach($villages as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                        @error('village_id') <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Detail Patokan / Alamat Lengkap Kejadian *</label>
                    <input wire:model="location_detail" type="text" placeholder="Contoh: Belakang Balai Desa Gogodeso, rumah gubuk sebelah sungai..." class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-rose-600 focus:outline-none text-sm">
                    @error('location_detail') <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Deskripsi Laporan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jelaskan Permasalahan / Kronologi Singkat *</label>
                    <textarea wire:model="description" rows="4" placeholder="Jelaskan kondisi orang yang membutuhkan pertolongan, keadaan fisik, kebutuhan mendesak..." class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-rose-600 focus:outline-none text-sm"></textarea>
                    @error('description') <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Lampiran Foto / Bukti -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Foto Bukti / Kondisi di Lapangan (Opsional)</label>
                    <span class="text-xs text-slate-500 block mb-3">Foto mempercepat verifikasi oleh tim lapangan Dinsos & TKSK setempat.</span>
                    <input wire:model="attachment" type="file" accept="image/*" class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-white hover:file:bg-slate-900">
                    @error('attachment') <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-slate-500">
                        Identitas pelapor akan dijaga kerahasiaannya sesuai ketentuan perlindungan saksi/pelapor.
                    </p>
                    <button 
                        type="submit" 
                        wire:loading.attr="disabled"
                        class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-700 hover:to-red-700 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-rose-500/30 transition-all hover:scale-[1.02] flex items-center justify-center gap-2 shrink-0"
                    >
                        <span wire:loading.remove>Kirim Laporan Pengaduan</span>
                        <span wire:loading>Mengirim Laporan...</span>
                        <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
