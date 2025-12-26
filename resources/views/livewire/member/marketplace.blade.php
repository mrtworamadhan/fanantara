<div class="h-screen bg-gray-50 flex flex-col relative overflow-hidden font-sans">
    <div class="bg-emerald-700 px-5 pt-5 pb-4 shadow-lg z-40 flex-none">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="p-2 rounded-full bg-white/10 text-white backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-xl font-bold text-white tracking-tight">Pasar Koperasi</h1>
            </div>
            <button class="relative p-2 text-white bg-white/10 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="absolute top-0 right-0 w-4 h-4 bg-amber-500 text-[10px] flex items-center justify-center rounded-full border border-emerald-600">0</span>
            </button>
        </div>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-emerald-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input wire:model.live="search" type="text" placeholder="Cari kebutuhan Anda..." class="w-full pl-10 pr-4 py-2.5 bg-emerald-700/30 border border-emerald-500/30 rounded-xl text-white placeholder-emerald-200 focus:outline-none focus:ring-2 focus:ring-white/20 transition-all">
        </div>
    </div>

    <div class="flex-1 overflow-y-auto no-scrollbar bg-gradient-to-b from-emerald-700 via-gray-50 to-white px-5 pt-4 pb-24">
        <div class="grid grid-cols-2 gap-4 animate-fade-in">
            @foreach($products as $product)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <img src="{{ asset('storage/' . $product->image) ?? 'https://placehold.co/400' }}" class="w-full h-40 object-cover">
                    
                    <div class="p-4">
                        <div class="flex items-center gap-1 mb-1">
                            <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">
                                {{ $product->supplier->name ?? 'Koperasi Pusat' }}
                            </span>
                        </div>

                        <h3 class="text-sm font-bold text-gray-800 leading-tight mb-2">{{ $product->name }}</h3>

                        <div class="space-y-1">
                            @if($memberType === 'institution')
                                <p class="text-emerald-600 font-black text-lg leading-none">
                                    Rp {{ number_format($product->sell_price_wholesale, 0, ',', '.') }}
                                </p>
                                <p class="text-[10px] text-gray-400 line-through">
                                    Rp {{ number_format($product->sell_price_retail, 0, ',', '.') }}
                                </p>
                            @else
                                <p class="text-emerald-600 font-black text-lg leading-none">
                                    Rp {{ number_format($product->sell_price_retail, 0, ',', '.') }}
                                </p>
                                <span class="text-[10px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-bold">
                                    Eceran
                                </span>
                            @endif
                        </div>

                        <button class="w-full mt-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold shadow-lg shadow-emerald-500/20 active:scale-95 transition-all">
                            + Keranjang
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <x-mobile.bottom-nav active="marketplace" />
</div>