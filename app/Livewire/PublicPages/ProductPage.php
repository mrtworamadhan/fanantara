<?php

namespace App\Livewire\PublicPages;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductPage extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';

    public function render()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        return view('livewire.public-pages.product-page', [
            'products' => $products,
        ])->layout('components.layouts.public', ['title' => 'Produk']);
    }
}
