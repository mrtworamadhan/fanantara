<?php

namespace App\Livewire\Member;

use App\Models\Product;
use App\Models\CartItem;
use App\Models\InventoryStock; 
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Filament\Notifications\Notification;

class Marketplace extends Component
{
    public $search = '';
    public $memberType;
    public $cartCount = 0;

    #[Layout('components.layouts.app')]

    public function mount()
    {
        $this->memberType = Auth::user()->member->type;
        $this->updateCartCount();
    }

    public function updateCartCount()
    {
        $this->cartCount = CartItem::where('member_id', Auth::user()->member->id)
            ->sum('quantity');
    }

    public function addToCart($productId)
    {
        $member = Auth::user()->member;
        
        $currentStock = InventoryStock::where('product_id', $productId)->sum('quantity');

        if ($currentStock <= 0) {
            $this->dispatch('cart-modal', [
                'type' => 'error',
                'title' => 'Stok Habis',
                'message' => 'Produk ini sedang tidak tersedia.'
            ]);
            return;
        }

        $cartItem = CartItem::where('member_id', $member->id)
            ->where('product_id', $productId)
            ->first();

        $qtyInCart = $cartItem ? $cartItem->quantity : 0;

        if (($qtyInCart + 1) > $currentStock) {
            $this->dispatch('cart-modal', [
                'type' => 'warning',
                'title' => 'Stok Tidak Cukup',
                'message' => 'Jumlah melebihi stok tersedia.'
            ]);
             return;
        }

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'member_id' => $member->id,
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        $this->updateCartCount();
        $this->dispatch('cart-modal', [
            'type' => 'success',
            'title' => 'Masuk Keranjang',
            'message' => 'Produk berhasil ditambahkan'
        ]);
    }

    public function render()
    {
        $products = Product::query()
            ->with('supplier')
            ->withSum('inventoryStocks as total_stock', 'quantity')
            
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->having('total_stock', '>', 0) 
            ->get();

        return view('livewire.member.marketplace', [
            'products' => $products
        ]);
    }
}