<?php

namespace App\Livewire;

use App\Enums\InformationPageCategory;
use App\Enums\PublishStatus;
use App\Models\InformationPage;
use App\Models\PageVisit;
use App\Models\SearchLog;
use Livewire\Component;

class InformationPortal extends Component
{
    public string $search = '';

    public string $category = 'all';

    public ?string $slug = null;

    public ?InformationPage $activeArticle = null;

    public function mount(?string $slug = null)
    {
        $this->slug = $slug;

        if ($this->slug) {
            $this->loadArticle($this->slug);
        }
    }

    public function loadArticle(string $slug)
    {
        $article = InformationPage::with(['serviceType', 'downloadableForms', 'faqs'])
            ->where('slug', $slug)
            ->where('publish_status', PublishStatus::Published)
            ->firstOrFail();

        $this->activeArticle = $article;

        // Catat kunjungan halaman (statistik harian untuk dashboard pimpinan)
        $today = now()->toDateString();
        $visit = PageVisit::firstOrCreate(
            ['information_page_id' => $article->id, 'visit_date' => $today],
            ['visit_count' => 0]
        );
        $visit->increment('visit_count');
    }

    public function filterCategory(string $category)
    {
        $this->category = $category;
        $this->activeArticle = null;
    }

    public function executeSearch()
    {
        $this->activeArticle = null;
        $term = trim($this->search);

        if (! empty($term)) {
            // Catat log pencarian kata kunci
            $count = InformationPage::where('publish_status', PublishStatus::Published)
                ->where(function ($q) use ($term) {
                    $q->where('title', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%")
                        ->orWhere('requirements', 'like', "%{$term}%");
                })->count();

            SearchLog::create([
                'keyword' => $term,
                'result_count' => $count,
                'searched_at' => now(),
            ]);
        }
    }

    public function render()
    {
        $query = InformationPage::with(['serviceType', 'downloadableForms', 'faqs'])
            ->where('publish_status', PublishStatus::Published);

        if ($this->category !== 'all') {
            $query->where('category', $this->category);
        }

        if (! empty(trim($this->search))) {
            $term = trim($this->search);
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('requirements', 'like', "%{$term}%");
            });
        }

        $articles = $query->latest('published_at')->get();

        $categories = InformationPageCategory::cases();

        return view('livewire.information-portal', [
            'articles' => $articles,
            'categories' => $categories,
        ])->layout('layouts.app', [
            'title' => $this->activeArticle
                ? $this->activeArticle->title.' — Informasi Layanan SAPA SOSIAL'
                : 'Pusat Informasi & Panduan Layanan Sosial — SAPA SOSIAL',
        ]);
    }
}
