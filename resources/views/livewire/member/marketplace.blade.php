<div class="h-screen bg-gray-50 flex flex-col relative overflow-hidden font-sans">
    <div class="bg-emerald-700 px-5 pt-5 pb-4 shadow-lg z-40 flex-none">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="p-2 rounded-full bg-white/10 text-white backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
                <h1 class="text-xl font-bold text-white tracking-tight">Pasar Koperasi</h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('member.orders.index') }}" class="p-2 text-white bg-white/10 rounded-full hover:bg-white/20 transition-all active:scale-95" title="Pesanan Saya">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </a>
                <a href="{{ route('member.shop.cart') }}"
                    class="relative p-2 text-white bg-white/10 rounded-full hover:bg-white/20 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>

                    @if($cartCount > 0)
                        <span
                            class="absolute top-0 right-0 w-4 h-4 bg-amber-500 text-[10px] flex items-center justify-center rounded-full border border-emerald-600 animate-bounce">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
            </div>
            
        </div>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-emerald-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input wire:model.live="search" type="text" placeholder="Cari kebutuhan Anda..."
                class="w-full pl-10 pr-4 py-2.5 bg-emerald-700/30 border border-emerald-500/30 rounded-xl text-white placeholder-emerald-200 focus:outline-none focus:ring-2 focus:ring-white/20 transition-all">
        </div>
    </div>

    <div class="flex-1 overflow-y-auto no-scrollbar bg-gray-100 px-4 pt-4 pb-24">
        <div class="grid grid-cols-2 gap-3">
            @foreach($products as $product)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition">

                    {{-- IMAGE --}}
                    <div class="relative">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400' }}"
                            class="w-full h-36 object-cover">

                        {{-- BADGE --}}
                        @if($memberType === 'institution')
                            <span class="absolute top-2 left-2 bg-red-500 text-white text-[9px] px-2 py-0.5 rounded font-bold">
                                Grosir
                            </span>
                        @endif
                    </div>

                    {{-- CONTENT --}}
                    <div class="p-3 space-y-1">

                        {{-- NAMA --}}
                        <h3 class="text-xs font-semibold text-gray-800 leading-snug line-clamp-2 min-h-[32px]">
                            {{ $product->name }}
                        </h3>

                        {{-- SUPPLIER --}}
                        <p class="text-[10px] text-gray-400">
                            {{ $product->supplier->name ?? 'Koperasi Pusat' }}
                        </p>

                        {{-- PRICE --}}
                        @if($memberType === 'institution')
                            <p class="text-emerald-600 font-extrabold text-sm">
                                Rp {{ number_format($product->sell_price_wholesale, 0, ',', '.') }}
                            </p>
                            <p class="text-[10px] text-gray-400 line-through">
                                Rp {{ number_format($product->sell_price_retail, 0, ',', '.') }}
                            </p>
                        @else
                            <p class="text-emerald-600 font-extrabold text-sm">
                                Rp {{ number_format($product->sell_price_retail, 0, ',', '.') }}
                            </p>
                        @endif

                        {{-- META --}}
                        <div class="flex items-center justify-between text-[10px] text-gray-500 mt-1">
                            <span>⭐ 4.8</span>
                            <span>Terjual {{ rand(10, 300) }}</span>
                        </div>

                        {{-- BUTTON --}}
                        <button wire:click="addToCart({{ $product->id }})" wire:loading.attr="disabled"
                            class="w-full mt-2 py-1.5 text-[11px] font-bold text-emerald-600 border border-emerald-600 rounded-lg hover:bg-emerald-50 active:scale-95 transition disabled:opacity-50">
                            <span wire:loading.remove wire:target="addToCart({{ $product->id }})">
                                + Keranjang
                            </span>
                            <span wire:loading wire:target="addToCart({{ $product->id }})">
                                Loading...
                            </span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <x-mobile.bottom-nav active="marketplace" />
    <style>
        @filamentStyle
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</div>