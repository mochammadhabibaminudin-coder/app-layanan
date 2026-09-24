<?php

namespace Database\Seeders;

use App\Enums\InformationPageCategory;
use App\Enums\PublishStatus;
use App\Models\DownloadableForm;
use App\Models\Faq;
use App\Models\InformationPage;
use App\Models\PageVisit;
use App\Models\SearchLog;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Database\Seeder;

class InformationPortalSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@dinsos.blitarkab.go.id')->first();
        $dtsenType = ServiceType::where('code', 'DTSEN')->first();
        $pbiType = ServiceType::where('code', 'PBI')->first();
        $rehsosType = ServiceType::where('code', 'REHSOS')->first();

        // 1. Artikel: Surat Keterangan DTSEN
        $page1 = InformationPage::create([
            'title' => 'Layanan Penerbitan Surat Keterangan DTSEN Kabupaten Blitar',
            'slug' => 'surat-keterangan-dtsen',
            'category' => InformationPageCategory::Program,
            'service_type_id' => $dtsenType?->id,
            'description' => 'Dinas Sosial Kabupaten Blitar melayani penerbitan Surat Keterangan Data Tunggal Sosial Ekonomi Nasional (DTSEN) untuk keperluan SPMB jalur afirmasi, Program Indonesia Pintar (PIP), KIP Kuliah, dan bantuan sosial.',
            'requirements' => "1. Foto/Scan KTP Pemohon (asli/fotokopi jelas)\n2. Foto/Scan Kartu Keluarga (KK) terbaru\n3. Surat permohonan atau surat keterangan dari sekolah/kampus (bila ada)",
            'procedure' => "1. Pemohon mengajukan permohonan melalui portal SAPA SOSIAL atau kantor Dinsos.\n2. Petugas memverifikasi kelengkapan berkas.\n3. Petugas mengecek status data pemohon di SIKS-NG.\n4. Draf surat dibuat dan diverifikasi oleh Kepala Bidang dan disetujui Kepala Dinas.\n5. Surat resmi ber-QR Code diterbitkan dan dapat diunduh pemohon.",
            'service_hours' => 'Senin - Kamis: 08.00 - 15.00 WIB, Jumat: 08.00 - 14.00 WIB',
            'location' => 'Kantor Dinas Sosial Kabupaten Blitar, Jl. Raya Kanigoro, Blitar',
            'contact' => 'WhatsApp: 0812-3456-7890 | Email: dinsos@blitarkab.go.id',
            'publish_status' => PublishStatus::Published,
            'published_at' => now()->subDays(30),
            'manager_id' => $admin?->id,
        ]);

        DownloadableForm::create([
            'information_page_id' => $page1->id,
            'name' => 'Formulir Permohonan Surat Keterangan DTSEN',
            'file_path' => 'forms/formulir_permohonan_dtsen.pdf',
            'version' => '1.2',
            'is_current' => true,
        ]);

        DownloadableForm::create([
            'information_page_id' => $page1->id,
            'name' => 'Surat Pernyataan Tanggung Jawab Mutlak (SPTJM)',
            'file_path' => 'forms/sptjm_dtsen.pdf',
            'version' => '1.0',
            'is_current' => true,
        ]);

        Faq::create([
            'information_page_id' => $page1->id,
            'question' => 'Berapa lama proses pembuatan Surat Keterangan DTSEN?',
            'answer' => 'Proses penerbitan surat keterangan DTSEN memerlukan waktu 1 (satu) hari kerja apabila berkas lengkap dan sistem SIKS-NG Kemensos normal.',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        Faq::create([
            'information_page_id' => $page1->id,
            'question' => 'Bagaimana jika nama saya belum terdaftar di DTSEN/SIKS-NG?',
            'answer' => 'Jika belum terdaftar, Anda dapat mengajukan usulan pendaftaran Data Terpadu melalui Musyawarah Desa/Kelurahan (Musdes/Muskel) di kantor desa/kelurahan domisili Anda.',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 2. Artikel: Reaktivasi KIS / PBI-JK
        $page2 = InformationPage::create([
            'title' => 'Panduan Reaktivasi Kepesertaan BPJS KIS PBI-JK yang Dinonaktifkan',
            'slug' => 'reaktivasi-kis-pbi-jk',
            'category' => InformationPageCategory::Program,
            'service_type_id' => $pbiType?->id,
            'description' => 'Fasilitasi pengaktifan kembali bagi masyarakat miskin dan rentan yang kartu BPJS Kesehatan KIS PBI-JK dinonaktifkan pemerintah pusat, terutama bagi yang membutuhkan penanganan medis segera.',
            'requirements' => "1. KTP dan Kartu Keluarga (KK)\n2. Kartu BPJS Kesehatan / KIS yang nonaktif\n3. Surat keterangan rawat inap atau surat rujukan faskes (wajib untuk kondisi darurat medis / sakit kronis)",
            'procedure' => "1. Pemohon mendaftar di SAPA SOSIAL dengan mengunggah syarat.\n2. Dinsos memeriksa kelayakan desil dan kondisi medis.\n3. Dinsos menerbitkan surat rekomendasi reaktivasi.\n4. Petugas mengusulkan reaktivasi melalui aplikasi SIKS-NG ke Kemensos RI.\n5. Setelah Kemensos menyetujui, BPJS Kesehatan mengaktifkan kepesertaan.",
            'service_hours' => 'Senin - Jumat: 08.00 - 15.00 WIB (Khusus darurat medis dilayani prioritas)',
            'location' => 'Loket Pelayanan Linjamsos, Kantor Dinas Sosial Kab. Blitar',
            'contact' => 'Helpdesk KIS: 0812-9988-7766',
            'publish_status' => PublishStatus::Published,
            'published_at' => now()->subDays(25),
            'manager_id' => $admin?->id,
        ]);

        DownloadableForm::create([
            'information_page_id' => $page2->id,
            'name' => 'Formulir Pengusulan Reaktivasi KIS PBI-JK',
            'file_path' => 'forms/formulir_reaktivasi_pbi.pdf',
            'version' => '2.0',
            'is_current' => true,
        ]);

        Faq::create([
            'information_page_id' => $page2->id,
            'question' => 'Siapa saja yang berhak mengajukan reaktivasi KIS PBI-JK?',
            'answer' => 'Masyarakat berpenghasilan rendah yang terdaftar dalam data desil kemiskinan dan mengalami kondisi sakit kronis, katastropik, darurat medis, atau bayi baru lahir dari peserta PBI.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 3. Artikel: Layanan Rehabilitasi Sosial
        $page3 = InformationPage::create([
            'title' => 'Pelayanan dan Rujukan Rehabilitasi Sosial Kabupaten Blitar',
            'slug' => 'pelayanan-rehabilitasi-sosial',
            'category' => InformationPageCategory::Rehabilitation,
            'service_type_id' => $rehsosType?->id,
            'description' => 'Penanganan terpadu untuk Pemerlu Pelayanan Kesejahteraan Sosial (PPKS): lansia terlantar, penyandang disabilitas, ODGJ terlantar, dan anak berhadapan dengan hukum.',
            'requirements' => "1. Identitas klien / KTP / KK (jika ada)\n2. Foto kondisi klien\n3. Laporan kejadian atau surat pengantar desa",
            'procedure' => "1. Laporan diterima dari masyarakat atau penjangkauan petugas.\n2. Assessment kondisi fisik, psikologis, dan sosial klien.\n3. Penanganan langsung atau rujukan ke panti / rumah sakit mitra.\n4. Monitoring perkembangan klien hingga mandiri.",
            'service_hours' => '24 Jam untuk Kedaruratan Sosial (Call Center Dinsos)',
            'location' => 'Shelter Penanganan Rehsos, Dinas Sosial Kab. Blitar',
            'contact' => 'Emergency Rehsos: 0811-2233-4455',
            'publish_status' => PublishStatus::Published,
            'published_at' => now()->subDays(20),
            'manager_id' => $admin?->id,
        ]);

        Faq::create([
            'information_page_id' => $page3->id,
            'question' => 'Bagaimana jika menemukan orang terlantar atau ODGJ yang mengamuk?',
            'answer' => 'Segera laporkan melalui menu Pengaduan Sosial di SAPA SOSIAL atau hubungi kontak darurat. Tim Reaksi Cepat (TRC) Dinsos akan berkoordinasi dengan Satpol PP dan Puskesmas terdekat.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // Page visits (Statistik kunjungan halaman untuk widget dashboard)
        for ($i = 14; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            PageVisit::create([
                'information_page_id' => $page1->id,
                'visit_date' => $date,
                'visit_count' => rand(45, 120),
            ]);
            PageVisit::create([
                'information_page_id' => $page2->id,
                'visit_date' => $date,
                'visit_count' => rand(30, 85),
            ]);
            PageVisit::create([
                'information_page_id' => $page3->id,
                'visit_date' => $date,
                'visit_count' => rand(15, 50),
            ]);
        }

        // Search logs (Pencarian kata kunci populer untuk widget dashboard)
        $keywords = [
            ['keyword' => 'DTSEN', 'count' => 142],
            ['keyword' => 'SPMB', 'count' => 98],
            ['keyword' => 'KIS PBI', 'count' => 87],
            ['keyword' => 'Reaktivasi', 'count' => 74],
            ['keyword' => 'Bansos', 'count' => 65],
            ['keyword' => 'KIP Kuliah', 'count' => 52],
            ['keyword' => 'Kursi Roda Disabilitas', 'count' => 38],
            ['keyword' => 'Lansia Terlantar', 'count' => 29],
            ['keyword' => 'PKH', 'count' => 24],
        ];

        foreach ($keywords as $kw) {
            SearchLog::create([
                'keyword' => $kw['keyword'],
                'result_count' => $kw['count'],
                'searched_at' => now()->subHours(rand(1, 48)),
            ]);
        }
    }
}
