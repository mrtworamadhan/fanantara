<?php

namespace App\Livewire\Member;

use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\Layout;

class News extends Component
{
    public $selectedArticle = null;
    #[Layout('components.layouts.app')]
    public function render()
    {
        $articles = Post::where('status', 'published')->latest()->get();
        return view('livewire.member.news', ['articles' => $articles]);
    }

    public function showArticle($id)
    {
        $this->selectedArticle = Post::find($id);
        $this->dispatch('open-article-modal');
    }

    public function closeArticle()
    {
        $this->selectedArticle = null;
    }
}