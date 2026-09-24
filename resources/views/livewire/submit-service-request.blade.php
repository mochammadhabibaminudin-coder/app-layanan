<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Title Header -->
        <div class="text-center max-w-xl mx-auto mb-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-bold mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                Formulir Mandiri
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pengajuan Permohonan Layanan</h1>
            <p class="mt-2 text-sm text-slate-600">
                Isi formulir pengajuan dengan data yang benar dan lengkap untuk mempercepat proses verifikasi oleh petugas.
            </p>
        </div>

        <!-- Service Selector Tabs -->
        <div class="bg-white rounded-2xl p-2.5 shadow-md border border-slate-200/80 mb-8 flex flex-wrap gap-2">
            @foreach($serviceTypes as $st)
                <button 
                    type="button" 
                    wire:click="changeService('{{ $st->code }}')" 
                    class="flex-1 min-w-[140px] py-3 px-4 rounded-xl text-xs sm:text-sm font-bold transition-all text-center {{ $serviceCode === $st->code ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-600 hover:bg-slate-100' }}"
                >
                    {{ $st->name }}
                </button>
            @endforeach
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-xl border border-slate-200/90">
            <form wire:submit="submit" class="space-y-10">

                <!-- BAGIAN 1: Identitas Pemohon -->
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2 mb-6 pb-3 border-b border-slate-100">
                        <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold">1</span>
                        Identitas Pemohon
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap Pemohon (Sesuai KTP) *</label>
                            <input wire:model="applicant_name" type="text" placeholder="Masukkan nama lengkap..." class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            @error('applicant_name') <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor Induk Kependudukan (NIK) *</label>
                            <input wire:model="applicant_nik" type="text" maxlength="16" placeholder="16 Digit NIK..." class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-mono font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            @error('applicant_nik') <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor Kartu Keluarga (No. KK) *</label>
                            <input wire:model="family_card_number" type="text" maxlength="16" placeholder="16 Digit No. KK..." class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-mono font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            @error('family_card_number') <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor WhatsApp / HP Aktif *</label>
                            <input wire:model="phone" type="text" placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            <span class="text-[11px] text-slate-400 mt-1 block">Pemberitahuan perkembangan status akan dikirimkan ke nomor ini.</span>
                            @error('phone') <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kecamatan Domisili *</label>
                            <select wire:model.live="district_id" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                @foreach($districts as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Desa / Kelurahan *</label>
                            <select wire:model="village_id" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                @foreach($villages as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                            @error('village_id') <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Lengkap (Jalan, RT/RW, Dusun) *</label>
                            <textarea wire:model="address" rows="2" placeholder="Tuliskan nama jalan, RT/RW..." class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none"></textarea>
                            @error('address') <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- BAGIAN 2: Detail Khusus Sesuai Layanan -->
                @if($serviceCode === 'DTSEN')
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2 mb-6 pb-3 border-b border-slate-100">
                            <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold">2</span>
                            Detail Keperluan Surat Keterangan DTSEN
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tujuan Penggunaan Surat *</label>
                                <select wire:model="dtsen_purpose_id" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                    @foreach($dtsenPurposes as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }} (Maks. Desil {{ $p->max_decile }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Hubungan dengan Pemohon *</label>
                                <select wire:model="relationship_to_applicant" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                    <option value="Diri Sendiri">Diri Sendiri</option>
                                    <option value="Anak Kandung">Anak Kandung</option>
                                    <option value="Suami / Istri">Suami / Istri</option>
                                    <option value="Orang Tua">Orang Tua</option>
                                    <option value="Famili Lain">Famili Lain dalam KK</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Orang yang Diterangkan (Siswa/Mahasiswa) *</label>
                                <input wire:model="subject_name" type="text" placeholder="Nama calon siswa/mahasiswa..." class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                @error('subject_name') <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">NIK Orang yang Diterangkan *</label>
                                <input wire:model="subject_nik" type="text" maxlength="16" placeholder="16 Digit NIK subjek..." class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-mono font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                @error('subject_nik') <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Keterangan Tambahan Keperluan</label>
                                <input wire:model="purpose_description" type="text" placeholder="Contoh: Pendaftaran SPMB SMAN 1 Garum Jalur Afirmasi..." class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            </div>
                        </div>
                    </div>
                @endif

                @if($serviceCode === 'PBI')
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2 mb-6 pb-3 border-b border-slate-100">
                            <span class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center text-xs font-bold">2</span>
                            Detail Kepesertaan BPJS / KIS Nonaktif
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Peserta KIS Nonaktif *</label>
                                <input wire:model="participant_name" type="text" placeholder="Nama pasien/peserta..." class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-rose-600 focus:outline-none">
                                @error('participant_name') <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">NIK Peserta *</label>
                                <input wire:model="participant_nik" type="text" maxlength="16" placeholder="16 Digit NIK peserta..." class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-mono font-medium focus:ring-2 focus:ring-rose-600 focus:outline-none">
                                @error('participant_nik') <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor Kartu BPJS / KIS *</label>
                                <input wire:model="bpjs_card_number" type="text" placeholder="13 Digit No. BPJS..." class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-mono font-medium focus:ring-2 focus:ring-rose-600 focus:outline-none">
                                @error('bpjs_card_number') <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alasan Reaktivasi *</label>
                                <select wire:model="pbi_reason" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-rose-600 focus:outline-none">
                                    <option value="emergency">Kondisi Darurat Medis RS (Prioritas Cepat)</option>
                                    <option value="chronic">Penyakit Kronis (Rutin Kontrol)</option>
                                    <option value="catastrophic">Penyakit Katastropik (Kanker, Jantung, Ginjal)</option>
                                    <option value="newborn">Bayi Baru Lahir dari Ibu Peserta PBI</option>
                                    <option value="other">Lainnya / Tidak Mampu</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Rumah Sakit / Puskesmas Perujuk</label>
                                <input wire:model="health_facility_name" type="text" placeholder="Contoh: RSUD Ngudi Waluyo Wlingi..." class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-rose-600 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor Surat Keterangan Rawat Inap / Faskes</label>
                                <input wire:model="health_letter_number" type="text" placeholder="Nomor surat keterangan dokter/RS..." class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-medium focus:ring-2 focus:ring-rose-600 focus:outline-none">
                            </div>

                            <div class="sm:col-span-2 p-4 rounded-2xl bg-rose-50 border border-rose-200">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input wire:model="is_emergency" type="checkbox" class="w-5 h-5 rounded text-rose-600 focus:ring-rose-500">
                                    <div>
                                        <strong class="text-rose-950 text-xs sm:text-sm font-bold block">Tandai sebagai Keadaan Kedaruratan Medis Mendesak</strong>
                                        <span class="text-xs text-rose-700">Centang jika pasien sedang dalam perawatan gawat darurat dan membutuhkan verifikasi segera.</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- BAGIAN 3: Unggah Berkas Persyaratan -->
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2 mb-6 pb-3 border-b border-slate-100">
                        <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold">3</span>
                        Unggah Berkas Persyaratan
                    </h3>

                    <div class="space-y-4">
                        @if($currentType)
                            @foreach($currentType->requirements as $req)
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-bold text-slate-900">{{ $req->name }}</span>
                                            @if($req->is_mandatory)
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-rose-100 text-rose-700">WAJIB</span>
                                            @else
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-200 text-slate-600">OPSIONAL</span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-slate-500 block mt-0.5">Format file: PDF, JPG, PNG (Maks 3MB)</span>
                                    </div>

                                    <div class="shrink-0 w-full sm:w-auto">
                                        <input wire:model="uploads.{{ $req->id }}" type="file" accept=".pdf,.jpg,.jpeg,.png" class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-slate-500">
                        Dengan menekan tombol kirim, saya menyatakan bahwa seluruh data yang diisikan adalah benar dan dapat dipertanggungjawabkan.
                    </p>
                    <button 
                        type="submit" 
                        wire:loading.attr="disabled"
                        class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-blue-500/30 transition-all hover:scale-[1.02] flex items-center justify-center gap-2 shrink-0"
                    >
                        <span wire:loading.remove>Kirim Pengajuan</span>
                        <span wire:loading>Memproses Data...</span>
                        <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
