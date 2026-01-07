<div>
    <!-- Hero Header - Compact Mobile First -->
    <section class="pt-24 pb-8 md:pt-28 md:pb-12 bg-gradient-to-br from-primary-50 via-white to-purple-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-primary-100 text-primary-700 text-sm font-bold rounded-full mb-4">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"/>
                    </svg>
                    Katalog Produk
                </span>
                <h1 class="text-2xl md:text-4xl font-black text-gray-900 mb-3">
                    Produk <span class="text-gradient">Pilihan</span> Koperasi
                </h1>
                <p class="text-gray-600 text-sm md:text-base max-w-xl mx-auto">
                    Produk berkualitas dengan harga terbaik khusus untuk Member Fanantara
                </p>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-8 md:py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-6 bg-primary-500 rounded-full"></div>
                    <h2 class="text-lg md:text-xl font-bold text-gray-800">Produk Terbaru</h2>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                    {{ $products->count() }} Produk
                </span>
            </div>

            <!-- Products Grid - Mobile First -->
            @if($products->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($products as $product)
                    <a href="#" class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <div class="aspect-square bg-gray-100 relative">
                            @if($product->image)
                            <img
                                src="{{ asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover"
                            />
                            @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                <i class="bi bi-image text-4xl text-gray-400"></i>
                            </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <div class="flex items-center gap-1 mb-1">
                                <svg class="w-3 h-3 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">
                                    {{ $product->supplier->name ?? 'Koperasi Pusat' }}
                                </span>
                            </div>

                            <h3 class="text-sm font-bold text-gray-800 leading-tight mb-2 line-clamp-2">{{ $product->name }}</h3>

                            <div class="space-y-1">
                                <p class="text-primary-600 font-black text-lg leading-none">
                                    Rp {{ number_format($product->sell_price_retail, 0, ',', '.') }}
                                </p>
                                <span class="text-[10px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-bold">
                                    Eceran
                                </span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-box-seam text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Produk</h3>
                    <p class="text-gray-500 text-sm">Produk akan segera tersedia</p>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 md:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-primary-600 to-teal-500 p-8 md:p-10 text-center">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>

                <div class="relative z-10">
                    <h2 class="text-xl md:text-2xl font-bold text-white mb-2">
                        Ingin Harga Lebih Hemat?
                    </h2>
                    <p class="text-white/90 text-sm mb-6 max-w-md mx-auto">
                        Daftar sebagai member dan nikmati harga khusus serta SHU tahunan
                    </p>

                    <a 
                        href="{{ route('register') }}" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-white text-primary-700 font-bold rounded-xl shadow-lg hover:bg-gray-50 transition-all"
                    >
                        <i class="bi bi-person-plus-fill"></i>
                        Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
