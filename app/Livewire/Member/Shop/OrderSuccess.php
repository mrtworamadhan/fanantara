<?php

namespace App\Livewire\Member\Shop;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderSuccess extends Component
{
    public Order $order;

    public function mount($orderId)
    {
        $this->order = Order::with('items.product')->findOrFail($orderId);

        if ($this->order->member_id !== Auth::user()->member->id) {
            return redirect()->route('member.marketplace');
        }
    }

    public function render()
    {
        return view('livewire.member.shop.order-success');
    }
}