<?php

namespace App\Livewire\Member\Shop;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\Models\InventoryStock;
use Filament\Notifications\Notification;

class Cart extends Component
{
    public $cartItems = [];
    
    public array $selectedItems = [];
    public bool $selectAll = false;

    public $itemToDeleteId = null; 
    public $isMassDelete = false;

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $member = Auth::user()->member;
        
        $this->cartItems = CartItem::with(['product' => function($q) {
                $q->withSum('inventoryStocks as total_stock', 'quantity');
            }])
            ->where('member_id', $member->id)
            ->get();

        $validIds = $this->cartItems->pluck('id')->toArray();
        $this->selectedItems = array_intersect($this->selectedItems, $validIds);
    }


    public function updatedSelectAll($value)
    {
        $this->selectedItems = $value
            ? $this->cartItems->pluck('id')->map(fn($id) => (string) $id)->toArray()
            : [];
    }

    public function updatedSelectedItems()
    {
        $this->selectAll = count($this->cartItems) > 0 && count($this->selectedItems) === count($this->cartItems);
    }

    public function getSelectedTotalProperty()
    {
        return $this->cartItems
            ->whereIn('id', $this->selectedItems)
            ->sum(function ($item) {
                return $item->product->sell_price_retail * $item->quantity;
            });
    }

    public function increment($itemId)
    {
        $item = CartItem::with('product')->find($itemId);
        $currentStock = InventoryStock::where('product_id', $item->product_id)->sum('quantity');

        if ($currentStock > $item->quantity) {
            $item->increment('quantity');
            $this->loadCart(); 
        } else {
            Notification::make()->title('Stok Maksimal Tercapai')->warning()->send();
        }
    }

    public function confirmRemove($itemId)
    {
        $this->dispatch('open-confirmation', [
            'title' => 'Hapus Produk?',
            'message' => 'Produk ini akan dihapus dari keranjang belanja Anda.',
            'action' => 'remove', 
            'params' => $itemId,
            'icon' => 'warning',
            'color' => 'red',
        ]);
    }

    public function executeDelete()
    {
        if ($this->isMassDelete) {
            // Logika Hapus Masal
            CartItem::whereIn('id', $this->selectedItems)->delete();
            $this->selectedItems = [];
            $this->selectAll = false;
            Notification::make()->title('Produk terpilih dihapus')->success()->send();
        } else {
            // Logika Hapus Satuan
            CartItem::destroy($this->itemToDeleteId);
            Notification::make()->title('Produk dihapus')->success()->send();
        }

        // Reset & Refresh
        $this->itemToDeleteId = null;
        $this->isMassDelete = false;
        $this->loadCart();
        $this->dispatch('cart-updated');
        
        // Tutup Modal
        $this->dispatch('close-modal', id: 'delete-confirmation');
    }

    public function confirmRemoveSelected()
    {
        $this->dispatch('open-confirmation', [
            'title' => 'Hapus ' . count($this->selectedItems) . ' Produk?',
            'message' => 'Semua produk yang dipilih akan dihapus permanen dari keranjang.',
            'action' => 'removeSelected', // Nama fungsi eksekusi
            'params' => null
        ]);
    }

    public function remove($itemId)
    {
        CartItem::destroy($itemId);
        $this->loadCart();
        $this->dispatch('cart-updated');
        
        Notification::make()->title('Produk berhasil dihapus')->success()->send();
    }

    public function decrement($itemId)
    {
        $item = CartItem::find($itemId);
        if ($item->quantity > 1) {
            $item->decrement('quantity');
        } else {
            $this->remove($itemId);
        }
        $this->loadCart();
    }


    public function removeSelected()
    {
        if (empty($this->selectedItems)) return;

        CartItem::whereIn('id', $this->selectedItems)->delete();
        
        $this->selectedItems = []; 
        $this->selectAll = false;
        
        $this->loadCart();
        $this->dispatch('cart-updated');
        
        Notification::make()->title('Item terpilih berhasil dihapus')->success()->send();
    }

    public function checkoutSelected()
    {
        if (empty($this->selectedItems)) {
            Notification::make()->title('Pilih produk dulu bro!')->warning()->send();
            return;
        }

        return redirect()->route('member.checkout', [
            'items' => $this->selectedItems
        ]);
    }

    public function render()
    {
        return view('livewire.member.shop.cart');
    }
}