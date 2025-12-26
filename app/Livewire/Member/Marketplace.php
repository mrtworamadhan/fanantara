<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class Marketplace extends Component
{
    public $search = '';
    public $category = 'all';

    #[Layout('components.layouts.app')]

    public function render()
    {
        $member = Auth::user()->member;

        $products = Product::query()
            ->where('is_active', true)
            // Eager Load supplier (Member) agar tidak N+1 Query saat nampilin nama produsen
            ->with(['supplier']) 
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get();

        return view('livewire.member.marketplace', [
            'products' => $products,
            'memberType' => $member->type // Kita kirim tipe member ke view
        ]);
    }
}