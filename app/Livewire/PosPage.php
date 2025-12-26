<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Member;
use App\Models\Order;
use App\Models\InventoryStock;
use App\Models\StockMovement;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\Payment;
use App\Models\AccountingPeriod;
use Illuminate\Support\Facades\DB;

class PosPage extends Component
{
    // State
    public $search = '';
    public $cart = [];
    public $member_id;
    public $payment_method = 'cash';    public $grand_total = 0;

    #[Layout('components.layouts.app')] 

    public function updatedSearch() { $this->render(); }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        
        $stock = InventoryStock::where('product_id', $productId)->first();
        if (!$stock || $stock->quantity <= 0) {
            session()->flash('error', 'Stok Habis!');
            return;
        }

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty']++;
            $this->cart[$productId]['subtotal'] = $this->cart[$productId]['qty'] * $this->cart[$productId]['price'];
        } else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image_url,
                'price' => $product->sell_price_retail,
                'base_price' => $product->base_price,
                'qty' => 1,
                'subtotal' => $product->sell_price_retail
            ];
        }
        $this->calculateTotal();
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        $this->calculateTotal();
    }

    public function updateQty($productId, $qty)
    {
        if ($qty > 0) {
            $this->cart[$productId]['qty'] = $qty;
            $this->cart[$productId]['subtotal'] = $qty * $this->cart[$productId]['price'];
            $this->calculateTotal();
        }
    }

    public function calculateTotal()
    {
        $this->grand_total = array_sum(array_column($this->cart, 'subtotal'));
    }

    public function checkout()
    {
        if (empty($this->cart)) return;
        if (!$this->member_id) {
            session()->flash('error', 'Pilih Anggota dulu!');
            return;
        }

        $order = DB::transaction(function () {
            
            $order = Order::create([
                'order_number'   => 'POS-' . time(),
                'member_id'      => $this->member_id,
                'warehouse_id'   => 1,
                'total_amount'   => $this->grand_total,
                'status'         => 'completed', 
                'payment_status' => 'unpaid', 
                'created_by'     => auth()->id(),
            ]);

            foreach ($this->cart as $item) {
                $order->items()->create([
                    'product_id' => $item['id'],
                    'quantity'   => $item['qty'],
                    'unit_price' => $item['price'],
                    'total_price'   => $item['subtotal'],
                ]);

                $stock = InventoryStock::where('product_id', $item['id'])->first();
                if ($stock) {
                    $stock->decrement('quantity', $item['qty']);
                    
                    StockMovement::create([
                        'inventory_stock_id' => $stock->id,
                        'user_id'            => auth()->id() ?? 1,
                        'type'               => 'out',
                        'quantity'           => $item['qty'],
                        'reference_number'   => $order->order_number,
                        'notes'              => 'POS Transaction',
                    ]);
                }
            }

            if ($this->payment_method === 'cash') {
                
                $accKas = Account::where('code', '1101')->first(); 

                if ($accKas) {
                    $order->payments()->create([
                        'amount'           => $this->grand_total,
                        'payment_date'     => now(),
                        'account_id'       => $accKas->id, 
                        'reference_number' => 'POS-' . $order->order_number,
                        'created_by'       => auth()->id() ?? 1,
                    ]);
                }
            }

            return $order;

        });
        $this->dispatch('transaction-success', orderId: $order->id);

        $this->cart = [];
        $this->grand_total = 0;
        $this->search = '';

        

        session()->flash('success', 'Transaksi Berhasil!');
    }

    public function render()
    {
        return view('livewire.pos-page', [
            'products' => Product::where('name', 'like', '%' . $this->search . '%')->take(12)->get(),
            'members' => Member::all(),
        ]);
    }
}