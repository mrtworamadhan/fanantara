<?php

namespace App\Livewire\PublicPages;

use App\Models\Post;
use Livewire\Component;

class ArticleDetailPage extends Component
{
    public Post $article;

    public function mount($slug)
    {
        $this->article = Post::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
    }

    public function render()
    {
        $relatedArticles = Post::where('id', '!=', $this->article->id)
            ->where('status', 'published')
            ->when($this->article->category, fn($q) => $q->where('category', $this->article->category))
            ->latest()
            ->take(3)
            ->get();

        return view('livewire.public-pages.article-detail-page', [
            'relatedArticles' => $relatedArticles,
        ])->layout('components.layouts.public', ['title' => $this->article->title]);
    }
}
