<?php

namespace Tests\Feature;

use App\Models\DtsenCertificate;
use App\Models\InformationPage;
use App\Models\ServiceRequest;
use Tests\TestCase;

class PublicPortalTest extends TestCase
{
    public function test_homepage_is_accessible_and_renders_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('SAPA SOSIAL');
        $response->assertSee('Kabupaten Blitar');
    }

    public function test_ticket_tracking_page_is_accessible(): void
    {
        $response = $this->get('/cek-status');

        $response->assertStatus(200);
        $response->assertSee('Cek Status & Lacak Tiket', false);
    }

    public function test_ticket_tracking_finds_valid_service_request(): void
    {
        $request = ServiceRequest::first();
        if (! $request) {
            $this->markTestSkipped('No service request in database.');
        }

        $response = $this->get('/cek-status?ticket='.$request->request_number);

        $response->assertStatus(200);
        $response->assertSee($request->request_number);
    }

    public function test_verify_document_finds_valid_dtsen_certificate(): void
    {
        $cert = DtsenCertificate::whereNotNull('verification_code')->first();
        if (! $cert) {
            $this->markTestSkipped('No certificate in database.');
        }

        $response = $this->get('/verifikasi/'.$cert->verification_code);

        $response->assertStatus(200);
        $response->assertSee($cert->verification_code);
    }

    public function test_information_portal_displays_published_articles(): void
    {
        $response = $this->get('/informasi');

        $response->assertStatus(200);
        $response->assertSee('Panduan & Informasi Layanan Sosial', false);
    }

    public function test_information_article_detail_page(): void
    {
        $article = InformationPage::first();
        if (! $article) {
            $this->markTestSkipped('No article in database.');
        }

        $response = $this->get('/informasi/'.$article->slug);

        $response->assertStatus(200);
        $response->assertSee($article->title);
    }

    public function test_service_application_page_is_accessible(): void
    {
        $response = $this->get('/pengajuan');

        $response->assertStatus(200);
        $response->assertSee('Pengajuan Permohonan Layanan');
    }

    public function test_complaint_submission_page_is_accessible(): void
    {
        $response = $this->get('/pengaduan');

        $response->assertStatus(200);
        $response->assertSee('Pengaduan & Laporan Masalah Sosial', false);
    }
}
