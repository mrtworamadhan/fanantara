<?php

namespace App\Livewire\Member\Shop;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\BankAccount;
use App\Models\User;
use Filament\Actions\Action;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\InventoryStock;
use App\Models\StockMovement;
use App\Models\SavingAccount; // Asumsi model akun simpanan
use Filament\Notifications\Notification;

class Checkout extends Component
{
    public $items = []; 
    
    public $checkoutItems = [];
    public $totalAmount = 0;
    
    public $shippingAddress;
    public $notes;
    public $paymentMethod = 'ss'; 
    public $saldoSukarela = 0;

    public $banks = [];

    protected $queryString = ['items']; 

    public function mount()
    {
        $member = Auth::user()->member;

        $this->checkoutItems = CartItem::with(['product' => function($q) {
                $q->withSum('inventoryStocks as total_stock', 'quantity');
            }])
            ->where('member_id', $member->id)
            ->whereIn('id', $this->items) 
            ->get();

        if ($this->checkoutItems->isEmpty()) {
            return redirect()->route('member.shop.cart');
        }

        $this->totalAmount = $this->checkoutItems->sum(fn($item) => $item->product->sell_price_retail * $item->quantity);

        $this->shippingAddress = $member->street_address . ', ' . 
                                 ($member->village->name ?? '') . ', ' . 
                                 ($member->city->name ?? '');

        $this->banks = BankAccount::where('is_active', true)->get();

        $accSS = $member->savingAccounts()->whereHas('savingType', fn($q) => $q->where('code', 'SS'))->first();
        $this->saldoSukarela = $accSS ? $accSS->balance : 0;
    }

    public function processOrder()
    {
        $member = Auth::user()->member;

        
        if ($this->paymentMethod === 'ss' && $this->saldoSukarela < $this->totalAmount) {
            Notification::make()->title('Saldo Simpanan Sukarela tidak cukup!')->danger()->send();
            return;
        }

        foreach ($this->checkoutItems as $item) {
            $currentStock = InventoryStock::where('product_id', $item->product_id)->sum('quantity');
            if ($currentStock < $item->quantity) {
                Notification::make()->title("Stok {$item->product->name} berubah/habis!")->danger()->send();
                return;
            }
        }

        DB::beginTransaction();

        try {
            $order = Order::create([
                'order_number'   => 'TRX-' . date('ymd') . '-' . rand(1000, 9999),
                'member_id'      => $member->id,
                'warehouse_id'   => 1, // Default Gudang Utama (Sesuaikan logika tokomu)
                'total_amount'   => $this->totalAmount,
                'status'         => 'pending',
                'payment_status' => $this->paymentMethod === 'ss' ? 'paid' : 'unpaid',
                'notes'          => $this->notes . ' | Alamat: ' . $this->shippingAddress,
                'created_by'     => Auth::id(),
            ]);

            foreach ($this->checkoutItems as $cartItem) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity'   => $cartItem->quantity,
                    'unit_price'      => $cartItem->product->sell_price_retail,
                    'total_price'      => $cartItem->quantity * $cartItem->product->sell_price_retail,
                ]);

                $stock = InventoryStock::where('product_id', $cartItem->product_id)
                        ->where('warehouse_id', 1)
                        ->lockForUpdate()
                        ->first();
                
                if ($stock) {
                    $stock->decrement('quantity', $cartItem->quantity);

                    StockMovement::create([
                        'inventory_stock_id' => $stock->id,
                        'user_id'            => Auth::id(),
                        'type'               => 'out',
                        'quantity'           => $cartItem->quantity,
                        'reference_number'   => $order->order_number,
                        'notes'              => 'Penjualan Marketplace Member',
                    ]);
                }
            }

            if ($this->paymentMethod === 'ss') {
                $accSS = $member->savingAccounts()->whereHas('savingType', fn($q) => $q->where('code', 'SS'))->first();
                
                $accSS->transactions()->create([
                    'transaction_date' => now(),
                    'type'             => 'withdrawal',
                    'amount'           => $this->totalAmount,
                    'reference_number' => $order->order_number,
                    'notes'            => 'Pembayaran Belanja #' . $order->order_number,
                    'created_by'       => Auth::id(),
                ]);
                
                $accSS->decrement('balance', $this->totalAmount);

                $order->update(['status' => 'processing']);
                
            }

            CartItem::whereIn('id', $this->items)->delete();

            DB::commit();
            

            return redirect()->route('member.order.success', ['orderId' => $order->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()->title('Gagal memproses: ' . $e->getMessage())->danger()->send();
        }
    }

    public function render()
    {
        return view('livewire.member.shop.checkout');
    }
}