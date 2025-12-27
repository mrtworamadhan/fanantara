<?php

namespace App\Livewire\Member\Shop;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class MyOrders extends Component
{
    use WithPagination;

    public $statusFilter = 'all'; // Default: Tampilkan semua
    public $selectedOrder = null; // Untuk Modal Detail
    public $isShowDetail = false;

    public function setFilter($status)
    {
        $this->statusFilter = $status;
        $this->resetPage(); // Reset pagination kalau ganti tab
    }

    public function showDetail($orderId)
    {
        $this->selectedOrder = Order::with('items.product')->find($orderId);
        
        // Security Check: Pastikan punya member sendiri
        if ($this->selectedOrder && $this->selectedOrder->member_id === Auth::user()->member->id) {
            $this->isShowDetail = true;
        }
    }

    public function closeDetail()
    {
        $this->isShowDetail = false;
        $this->selectedOrder = null;
    }

    public function render()
    {
        $memberId = Auth::user()->member->id;

        $orders = Order::where('member_id', $memberId)
            ->with(['items.product']) // Eager Load biar ringan
            ->when($this->statusFilter !== 'all', function ($q) {
                // Mapping Tab ke Status Database
                return match($this->statusFilter) {
                    'pending' => $q->whereIn('status', ['pending']), // Menunggu Bayar
                    'process' => $q->whereIn('status', ['processing']), // Sedang Diproses
                    'done'    => $q->whereIn('status', ['completed', 'cancelled']), // Riwayat Selesai
                    default   => $q
                };
            })
            ->latest()->get();

        return view('livewire.member.shop.my-orders', [
            'orders' => $orders
        ]);
    }
}