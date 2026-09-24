<?php

use App\Livewire\CheckTicketStatus;
use App\Livewire\HomePage;
use App\Livewire\InformationPortal;
use App\Livewire\SubmitComplaint;
use App\Livewire\SubmitServiceRequest;
use App\Livewire\VerifyDocument;
use Illuminate\Support\Facades\Route;

// Portal Publik SAPA SOSIAL Kab. Blitar
Route::get('/', HomePage::class)->name('home');
Route::get('/cek-status', CheckTicketStatus::class)->name('tracking');
Route::get('/verifikasi/{code?}', VerifyDocument::class)->name('verify');
Route::get('/pengajuan/{type?}', SubmitServiceRequest::class)->name('service.apply');
Route::get('/pengaduan', SubmitComplaint::class)->name('complaint.create');
Route::get('/informasi', InformationPortal::class)->name('information.index');
Route::get('/informasi/{slug}', InformationPortal::class)->name('information.show');
