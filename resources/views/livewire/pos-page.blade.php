<div class="flex h-screen">
    
    <div class="w-2/3 bg-gray-50 flex flex-col border-r">
        <div class="p-4 bg-white shadow-sm z-10 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Koperasi POS</h1>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari Produk / Scan Barcode..." 
                class="w-1/2 p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div class="flex-1 overflow-y-auto p-4">
            @if(session()->has('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if(session()->has('error'))
                <div class="bg-red-100 text-red-800 p-3 rounded mb-4">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-3 gap-4">
                @foreach($products as $product)
                <div wire:click="addToCart({{ $product->id }})" 
                     class="bg-white p-4 rounded-xl shadow hover:shadow-lg cursor-pointer transition transform hover:-translate-y-1 border border-transparent hover:border-blue-500">
                    
                    <div class="h-32 bg-gray-200 rounded-md mb-3 flex items-center justify-center overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="object-cover h-full w-full">
                        @else
                            <span class="text-gray-400 text-4xl">📦</span>
                        @endif
                    </div>
                    
                    <h3 class="font-bold text-gray-700 truncate">{{ $product->name }}</h3>
                    <p class="text-blue-600 font-bold">Rp {{ number_format($product->sell_price_retail, 0) }}</p>
                    <p class="text-xs text-gray-400">Stok: Tersedia</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="w-1/3 bg-white flex flex-col shadow-2xl z-20">
        <div class="p-4 border-b bg-gray-50">
            <label class="text-xs font-bold text-gray-500 uppercase">Pelanggan</label>
            <select wire:model="member_id" class="w-full p-2 border rounded mt-1 bg-white">
                <option value="">-- Pilih Tamu / Member --</option>
                @foreach($members as $member)
                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            @forelse($cart as $id => $item)
            <div class="flex justify-between items-center border-b pb-2">
                <div>
                    <h4 class="font-bold text-gray-800">{{ $item['name'] }}</h4>
                    <div class="text-sm text-gray-500">
                        Rp {{ number_format($item['price'], 0) }} x 
                        <input type="number" wire:change="updateQty({{ $id }}, $event.target.value)" 
                               value="{{ $item['qty'] }}" class="w-12 text-center border rounded p-1 mx-1">
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold">Rp {{ number_format($item['subtotal'], 0) }}</p>
                    <button wire:click="removeFromCart({{ $id }})" class="text-red-500 text-xs hover:underline">Hapus</button>
                </div>
            </div>
            @empty
            <div class="h-full flex flex-col items-center justify-center text-gray-400">
                <span class="text-4xl mb-2">🛒</span>
                <p>Keranjang Kosong</p>
            </div>
            @endforelse
        </div>

        <div class="p-6 bg-gray-50 border-t">
            <div class="flex justify-between mb-4 text-xl font-bold">
                <span>Total</span>
                <span>Rp {{ number_format($grand_total, 0) }}</span>
            </div>

            <div class="mb-4">
                <label class="text-xs font-bold text-gray-500 uppercase">Metode Bayar</label>
                <div class="flex gap-2 mt-1">
                    <button wire:click="$set('payment_method', 'cash')" 
                        class="flex-1 py-2 rounded border {{ $payment_method === 'cash' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600' }}">
                        Tunai
                    </button>
                    <button wire:click="$set('payment_method', 'tempo')" 
                        class="flex-1 py-2 rounded border {{ $payment_method === 'tempo' ? 'bg-orange-500 text-white border-orange-500' : 'bg-white text-gray-600' }}">
                        Tempo
                    </button>
                </div>
            </div>

            <button wire:click="checkout" wire:loading.attr="disabled"
                class="w-full py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg transition text-lg flex justify-center items-center gap-2">
                <span wire:loading.remove>BAYAR SEKARANG</span>
                <span wire:loading>Memproses...</span>
            </button>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('transaction-success', (event) => {
        // let audio = new Audio('/sounds/beep.mp3'); audio.play();

        // Ambil Order ID dari parameter event
        // Note: Livewire v3 kirim parameter event dalam array/object
        let orderId = event.orderId; 

        // Tampilkan Notif SweetAlert (Biar keren) - Opsional
        // alert('Transaksi Berhasil!'); 

        // BUKA TAB PRINT STRUK
        let url = "{{ route('print.receipt', ':id') }}";
        url = url.replace(':id', orderId);
        
        // Buka popup print window kecil
        window.open(url, '_blank', 'width=400,height=600');
    });
</script>
@endscript