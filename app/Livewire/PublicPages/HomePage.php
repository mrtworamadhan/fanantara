<?php

namespace App\Livewire\PublicPages;

use App\Models\Post;
use App\Models\Product;
use Livewire\Component;

class HomePage extends Component
{
    public function render()
    {
        $products = Product::where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        $posts = Post::where('status', 'published')
            ->with('author')
            ->latest()
            ->take(3)
            ->get();

        return view('livewire.public-pages.home-page', [
            'products' => $products,
            'posts' => $posts,
        ])->layout('components.layouts.public', ['title' => 'Fanantara']);
    }
}
