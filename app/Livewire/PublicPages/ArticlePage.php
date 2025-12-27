<?php

namespace App\Livewire\PublicPages;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class ArticlePage extends Component
{
    use WithPagination;

    public $category = '';

    public function render()
    {
        $articles = Post::query()
            ->when($this->category, fn($q) => $q->where('category', $this->category))
            ->where('status', 'published')
            ->latest()
            ->paginate(9);

        return view('livewire.public-pages.article-page', [
            'articles' => $articles,
        ])->layout('components.layouts.public', ['title' => 'Artikel']);
    }
}
