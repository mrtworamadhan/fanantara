<div class="h-screen flex flex-col bg-gray-50 font-sans overflow-hidden">

    {{-- Header Purple --}}
    <div class="bg-purple-700 px-5 pt-5 pb-4 shadow-lg z-40 flex-none">
        <div class="flex items-center gap-3">
            <a href="{{ route('member.shop.cart') }}" class="p-2 -ml-2 rounded-full bg-white/10 text-white backdrop-blur-sm active:scale-90 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold text-white tracking-tight">Pengiriman & Pembayaran</h1>
        </div>
    </div>


    <div class="flex-1 overflow-y-auto px-5 py-6 pb-48 no-scrollbar">

        {{-- 1. Alamat --}}
        <div class="bg-white p-5 mb-3 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Alamat Pengiriman</h3>
            <textarea wire:model="shippingAddress" rows="3"
                class="w-full bg-gray-50 p-3  border-gray-200 rounded-xl focus:ring-purple-500 focus:border-purple-500 text-sm"
                placeholder="Detail alamat lengkap..."></textarea>

            <div class="mt-4">
                <label class="text-xs font-bold text-gray-500">
                    Catatan Pesanan (Opsional)
                </label>

                <textarea wire:model="notes" rows="3" class="w-full mt-1 bg-gray-50 p-3 border-gray-200 rounded-xl
                            focus:ring-purple-500 focus:border-purple-500 text-sm
                            resize-none" placeholder="Contoh: Titip di pos satpam"></textarea>
            </div>

        </div>

        <div class="bg-white p-5 mb-3 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Metode Pembayaran</h3>

            <div class="space-y-3">
                <label
                    class="flex items-center justify-between p-4 border rounded-xl cursor-pointer transition-all {{ $paymentMethod === 'ss' ? 'border-purple-500 bg-purple-50 ring-1 ring-purple-500' : 'border-gray-200 hover:border-gray-300' }}">
                    <div class="flex items-center gap-3">
                        <input type="radio" wire:model.live="paymentMethod" value="ss"
                            class="text-purple-600 focus:ring-purple-500 w-5 h-5">
                        <div>
                            <p class="font-bold text-gray-800 text-sm">Saldo Simpanan Sukarela</p>
                            <p class="text-[10px] text-gray-500 font-medium">Saldo: <span class="font-bold text-green-600">Rp {{ number_format($saldoSukarela, 0, ',', '.') }}</span></p>
                        </div>
                    </div>
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </label>

                <label
                    class="flex items-center justify-between p-4 border rounded-xl cursor-pointer transition-all {{ $paymentMethod === 'transfer' ? 'border-purple-500 bg-purple-50 ring-1 ring-purple-500' : 'border-gray-200 hover:border-gray-300' }}">
                    <div class="flex items-center gap-3">
                        <input type="radio" wire:model.live="paymentMethod" value="transfer"
                            class="text-purple-600 focus:ring-purple-500 w-5 h-5">
                        <div>
                            <p class="font-bold text-gray-800 text-sm">Transfer Bank Manual</p>
                            <p class="text-[10px] text-gray-500 font-medium uppercase tracking-tighter">Konfirmasi via WhatsApp Admin</p>
                        </div>
                    </div>
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </label>
            </div>

            @if($paymentMethod === 'ss' && $saldoSukarela < $totalAmount)
                <div class="mt-4 p-3 bg-red-50 text-red-600 text-[11px] font-bold rounded-xl flex items-center gap-2 border border-red-100 animate-pulse">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Saldo tidak mencukupi untuk transaksi ini.
                </div>
            @endif

            @if($paymentMethod === 'transfer')
                <div class="mt-6 space-y-3 animate-fade-in" x-data>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Silakan Transfer Ke:</p>
                    
                    @foreach($banks as $bank)
                        <div class="p-4 bg-gray-50 border border-gray-100 rounded-2xl relative">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center p-1.5 border border-gray-100">
                                    @if($bank->logo)
                                        <img src="{{ asset('storage/' . $bank->logo) }}" class="max-h-full object-contain">
                                    @else
                                        <span class="text-[8px] font-bold text-gray-400 text-center uppercase">{{ $bank->bank_name }}</span>
                                    @endif
                                </div>
                                
                                <div class="flex-1">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase leading-none mb-1">{{ $bank->bank_name }}</p>
                                    <p class="text-sm font-black text-gray-900 tracking-wider font-mono">{{ $bank->account_number }}</p>
                                    <p class="text-[10px] text-gray-500 font-medium leading-none mt-1">a.n {{ $bank->account_holder }}</p>
                                </div>

                                <button type="button" 
                                        @click="navigator.clipboard.writeText('{{ $bank->account_number }}'); alert('Nomor rekening {{ $bank->bank_name }} berhasil disalin!')"
                                        class="p-2 text-purple-600 bg-white shadow-sm border border-purple-50 rounded-xl active:scale-90 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach

                    <div class="p-3 bg-purple-50 border border-purple-100 rounded-xl">
                        <p class="text-[10px] text-purple-700 leading-relaxed font-medium">
                            * Setelah melakukan transfer, harap simpan bukti pembayaran dan konfirmasi ke Admin untuk aktivasi pesanan.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <div class="bg-white p-5 mb-3 rounded-2xl shadow-sm border border-gray-100 sticky top-24">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Ringkasan Pesanan</h3>

            <div class="space-y-3 max-h-60 overflow-y-auto pr-1 mb-4 custom-scrollbar">
                @foreach($checkoutItems as $item)
                    <div class="flex gap-3">
                        <img src="{{ asset('storage/' . $item->product->image) }}"
                            class="w-12 h-12 rounded-lg bg-gray-100 object-cover flex-none">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-gray-800 line-clamp-2">{{ $item->product->name }}</p>
                            <p class="text-[10px] text-gray-500">{{ $item->quantity }} x Rp
                                {{ number_format($item->product->sell_price_retail, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-gray-800">Rp
                                {{ number_format($item->product->sell_price_retail * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-gray-100 pt-3 space-y-2">
                <div class="flex justify-between text-xs text-gray-500">
                    <span>Subtotal Produk</span>
                    <span>Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xs text-green-600 font-bold">
                    <span>Biaya Layanan</span>
                    <span>Gratis</span>
                </div>
                <div
                    class="flex justify-between text-base font-black text-gray-900 pt-2 border-t border-dashed border-gray-200">
                    <span>Total Bayar</span>
                    <span>Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="h-40"></div>
    </div>

    <div
        class="fixed bottom-0 left-0 right-0 p-5 bg-white/95 backdrop-blur-md border-t border-gray-100 max-w-md mx-auto z-50 rounded-t-[2.5rem] shadow-[0_-10px_30px_rgba(0,0,0,0.05)]">
        <div class="flex justify-between items-center mb-4">
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                    Total Bayar
                </p>
                <p class="text-xl font-black text-gray-900">
                    Rp {{ number_format($totalAmount, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <button wire:click="processOrder" wire:loading.attr="disabled" {{ ($paymentMethod === 'ss' && $saldoSukarela < $totalAmount) ? 'disabled' : '' }}
            class="w-full py-4 bg-purple-600 hover:bg-purple-700 disabled:bg-gray-300 disabled:text-gray-500 text-white font-black rounded-2xl shadow-lg shadow-purple-500/30 transition-all flex justify-center items-center gap-2">
            <span wire:loading.remove>BAYAR SEKARANG</span>
            <span wire:loading>MEMPROSES...</span>
        </button>

        <div class="h-4"></div>
    </div>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</div>
    