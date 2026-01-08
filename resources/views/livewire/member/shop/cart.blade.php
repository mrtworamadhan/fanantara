<div class="h-screen bg-gray-50 flex flex-col relative overflow-hidden font-sans">
    
    {{-- ================= HEADER ================= --}}
    <div class="bg-purple-600 px-5 pt-5 pb-6 shadow-lg z-40 flex-none relative rounded-b-[2.5rem]">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('member.marketplace') }}" class="p-2 rounded-full bg-white/10 text-white backdrop-blur-sm active:scale-90 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">Keranjang</h1>
                    <p class="text-[10px] text-purple-100 uppercase tracking-widest font-medium">{{ count($cartItems) }} Produk Total</p>
                </div>
            </div>

            {{-- TOMBOL HAPUS MASSAL (Muncul jika ada yg dipilih) --}}
            @if(count($selectedItems) > 0)
                <button wire:click="confirmRemoveSelected" 
                    class="text-white bg-red-500/80 hover:bg-red-600 px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Hapus
                </button>
            @endif
        </div>

        {{-- CHECKBOX SELECT ALL --}}
        @if(count($cartItems) > 0)
            <div class="mt-4 flex items-center gap-3">
                <input type="checkbox" wire:model.live="selectAll" id="selectAll" 
                    class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500 bg-white/90">
                <label for="selectAll" class="text-white text-sm font-bold cursor-pointer select-none">Pilih Semua Barang</label>
            </div>
        @endif
    </div>

    {{-- ================= LIST BARANG ================= --}}
    <div class="flex-1 overflow-y-auto no-scrollbar bg-gray-50 relative w-full pb-40 mb-6">
        <div class="px-5 py-4 space-y-3">
            
            @forelse($cartItems as $item)
                <div class="bg-white p-3 rounded-2xl border border-gray-100 shadow-sm flex gap-3 animate-fade-in items-center">
                    
                    {{-- CHECKBOX PER ITEM --}}
                    <div class="flex-none">
                        <input type="checkbox" value="{{ $item->id }}" wire:model.live="selectedItems" 
                            class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                    </div>

                    {{-- GAMBAR --}}
                    <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 flex-none border border-gray-50">
                        <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                    </div>

                    {{-- INFO --}}
                    <div class="flex-1 flex flex-col justify-between py-0.5 min-w-0">
                        <div>
                            <h3 class="text-sm font-bold text-gray-800 line-clamp-1 leading-tight">{{ $item->product->name }}</h3>
                            <p class="text-[10px] text-purple-600 font-bold mt-0.5">
                                Rp {{ number_format($item->product->sell_price_retail, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- QTY & SUBTOTAL --}}
                        <div class="flex justify-between items-end mt-2">
                            {{-- Subtotal Kecil --}}
                            <p class="text-xs font-black text-gray-800">
                                Total: Rp {{ number_format($item->product->sell_price_retail * $item->quantity, 0, ',', '.') }}
                            </p>
                            
                            <div class="flex items-center gap-2 bg-gray-50 border border-gray-100 px-1.5 py-1 rounded-lg">
                                <button wire:click="decrement({{ $item->id }})" class="w-6 h-6 flex items-center justify-center text-purple-600 active:scale-75 transition-all">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path></svg>
                                </button>
                                <span class="text-xs font-bold text-gray-700 w-4 text-center">{{ $item->quantity }}</span>
                                <button wire:click="increment({{ $item->id }})" 
                                    class="w-6 h-6 flex items-center justify-center text-purple-600 active:scale-75 transition-all 
                                    {{ ($item->product->total_stock ?? 0) <= $item->quantity ? 'opacity-30 cursor-not-allowed' : '' }}"
                                    {{ ($item->product->total_stock ?? 0) <= $item->quantity ? 'disabled' : '' }}>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                                <button wire:click="confirmRemove({{ $item->id }})" class="text-gray-300 hover:text-red-500 transition-colors p-1 -mr-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Kosong --}}
                <div class="flex flex-col items-center justify-center py-20 opacity-40 text-center space-y-4">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center text-purple-600">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">Keranjang Masih Kosong</p>
                        <a href="{{ route('member.marketplace') }}" class="mt-2 inline-block px-4 py-2 bg-purple-600 text-white rounded-lg text-xs font-bold">Mulai Belanja</a>
                    </div>
                </div>
            @endforelse

        </div>

        {{-- Spacer untuk fixed bottom button --}}
        <div class="h-40"></div>
    </div>

    {{-- ================= FOOTER DINAMIS ================= --}}
    @if(count($cartItems) > 0)
        <div class="fixed bottom-0 left-0 right-0 p-5 bg-white/95 backdrop-blur-md border-t border-gray-100 max-w-md mx-auto z-50 rounded-t-[2.5rem] shadow-[0_-10px_30px_rgba(0,0,0,0.05)]">
            <div class="flex justify-between items-center mb-5 px-2">
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Total Dipilih ({{ count($selectedItems) }})</p>
                    {{-- TAMPILKAN TOTAL DARI COMPUTED PROPERTY --}}
                    <p class="text-xl font-black text-gray-900 leading-none mt-1 animate-pulse-fast">
                        Rp {{ number_format($this->selectedTotal, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <button 
                wire:click="checkoutSelected" 
                {{ empty($selectedItems) ? 'disabled' : '' }}
                class="w-full py-4 font-black rounded-2xl shadow-lg transition-all flex justify-center items-center gap-3 transform active:scale-[0.98]
                {{ empty($selectedItems) 
                    ? 'bg-gray-300 text-gray-500 cursor-not-allowed shadow-none' 
                    : 'bg-purple-600 hover:bg-purple-700 text-white shadow-purple-500/30' }}">
                
                <span wire:loading.remove>
                    {{ empty($selectedItems) ? 'PILIH BARANG DULU' : 'CHECKOUT (' . count($selectedItems) . ')' }}
                </span>
                <span wire:loading>MEMPROSES...</span>
            </button>
            <div class="h-4"></div>
        </div>
    @endif
    <x-ui.modal-confirm />
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .animate-bounce-slow { animation: bounce 2s infinite; }
        @keyframes bounce { 
            0%, 100% { 
                transform: translateY(-5%); animation-timing-function: cubic-bezier(0.8, 0, 1, 1); 
                }
            50% { 
                transform: translateY(0); animation-timing-function: cubic-bezier(0, 0, 0.2, 1); 
            } 
        }
    </style>
</div>