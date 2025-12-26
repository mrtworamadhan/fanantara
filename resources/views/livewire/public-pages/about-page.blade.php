<div>
    <!-- Hero Header - Compact Mobile First -->
    <section class="pt-24 pb-8 md:pt-28 md:pb-12 bg-gradient-to-br from-emerald-50 via-white to-purple-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-700 text-sm font-bold rounded-full mb-4">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                    </svg>
                    Koperasi Multi Pihak
                </span>
                <h1 class="text-2xl md:text-4xl font-black text-gray-900 mb-3">
                    Tentang <span class="text-gradient">Fanantara</span>
                </h1>
                <p class="text-gray-600 text-sm md:text-base max-w-xl mx-auto">
                    Menghubungkan produsen, distributor, dan konsumen dalam ekosistem ekonomi yang adil
                </p>
            </div>
        </div>
    </section>

    <!-- Visi Misi Section -->
    <section class="py-10 md:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Visi -->
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 p-6 md:p-8 rounded-2xl">
                    <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center mb-4">
                        <i class="bi bi-eye-fill text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Visi</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ \App\Models\SiteSetting::get('visi', 'Menjadi koperasi multi pihak terdepan yang memberdayakan seluruh anggota melalui ekosistem ekonomi yang inklusif, adil, dan berkelanjutan.') }}
                    </p>
                </div>
                
                <!-- Misi -->
                <div class="bg-gradient-to-br from-amber-50 to-yellow-50 p-6 md:p-8 rounded-2xl">
                    <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center mb-4">
                        <i class="bi bi-bullseye text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Misi</h3>
                    <ul class="space-y-2 text-gray-600 text-sm">
                        @php
                            $misiItems = json_decode(\App\Models\SiteSetting::get('misi', '[]'), true) ?? [];
                        @endphp
                        @forelse ($misiItems as $misi)
                        <li class="flex items-start gap-2">
                            <i class="bi bi-check-circle-fill text-emerald-500 mt-0.5"></i>
                            <span>{{ $misi }}</span>
                        </li>
                        @empty
                        <li class="flex items-start gap-2">
                            <i class="bi bi-check-circle-fill text-emerald-500 mt-0.5"></i>
                            <span>Memfasilitasi akses produk berkualitas dengan harga kompetitif</span>
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Apa itu Koperasi Multi Pihak -->
    <section class="py-10 md:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-8">
                <span class="inline-block px-3 py-1.5 bg-purple-100 text-purple-700 text-xs font-bold rounded-full mb-3">
                    Multi-Stakeholder Cooperative
                </span>
                <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-2">
                    Apa Itu Koperasi <span class="text-gradient">Multi Pihak</span>?
                </h2>
                <p class="text-gray-600 text-sm max-w-xl mx-auto">
                    Model koperasi modern yang mengintegrasikan berbagai pihak dalam satu wadah organisasi demokratis
                </p>
            </div>

            <!-- Ecosystem Cards -->
            <div class="grid md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                <div class="bg-white p-5 rounded-xl border border-gray-100 text-center">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="bi bi-factory text-emerald-600 text-xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-1">Produsen & UMKM</h4>
                    <p class="text-xs text-gray-500">Memasok produk berkualitas</p>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-100 text-center">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="bi bi-truck text-amber-600 text-xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-1">Distributor</h4>
                    <p class="text-xs text-gray-500">Menyalurkan ke pasar</p>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-100 text-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="bi bi-people-fill text-purple-600 text-xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-1">Member & Konsumen</h4>
                    <p class="text-xs text-gray-500">Menikmati harga khusus & SHU</p>
                </div>
            </div>

            <!-- Values Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-8 max-w-2xl mx-auto">
                <div class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-100">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <i class="bi bi-people-fill text-emerald-600 text-sm"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-700">Multi Stakeholder</span>
                </div>
                <div class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-100">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                        <i class="bi bi-graph-up text-amber-600 text-sm"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-700">Pembagian SHU</span>
                </div>
                <div class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-100">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                        <i class="bi bi-shield-check text-purple-600 text-sm"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-700">Transparan</span>
                </div>
                <div class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-100">
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                        <i class="bi bi-heart-fill text-red-500 text-sm"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-700">Gotong Royong</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Jenis Member -->
    <section class="py-10 md:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-8">
                <span class="inline-block px-3 py-1.5 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full mb-3">
                    Keanggotaan
                </span>
                <h2 class="text-xl md:text-2xl font-bold text-gray-900">
                    Jenis <span class="text-gradient">Member</span>
                </h2>
            </div>

            <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                <!-- Individual -->
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 p-6 rounded-2xl border border-emerald-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center">
                            <i class="bi bi-person-fill text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Member Individual</h3>
                            <p class="text-xs text-gray-500">Untuk perorangan</p>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 text-sm mb-4">
                        Keanggotaan bagi perorangan yang ingin menjadi bagian dari ekosistem Fanantara.
                    </p>

                    <ul class="space-y-2 mb-5">
                        <li class="flex items-center gap-2 text-gray-600 text-sm">
                            <i class="bi bi-check-circle-fill text-emerald-500"></i>
                            Akses harga khusus member
                        </li>
                        <li class="flex items-center gap-2 text-gray-600 text-sm">
                            <i class="bi bi-check-circle-fill text-emerald-500"></i>
                            Pembagian SHU tahunan
                        </li>
                        <li class="flex items-center gap-2 text-gray-600 text-sm">
                            <i class="bi bi-check-circle-fill text-emerald-500"></i>
                            Hak suara di RAT
                        </li>
                    </ul>
                </div>

                <!-- Institution -->
                <div class="bg-gradient-to-br from-purple-50 to-violet-50 p-6 rounded-2xl border border-purple-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center">
                            <i class="bi bi-building text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Member Institution</h3>
                            <p class="text-xs text-gray-500">Untuk badan usaha</p>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 text-sm mb-4">
                        Keanggotaan bagi badan usaha, UMKM, CV, PT, atau organisasi mitra strategis.
                    </p>

                    <ul class="space-y-2 mb-5">
                        <li class="flex items-center gap-2 text-gray-600 text-sm">
                            <i class="bi bi-check-circle-fill text-purple-500"></i>
                            Kerjasama B2B dengan mitra
                        </li>
                        <li class="flex items-center gap-2 text-gray-600 text-sm">
                            <i class="bi bi-check-circle-fill text-purple-500"></i>
                            Akses pasar yang lebih luas
                        </li>
                        <li class="flex items-center gap-2 text-gray-600 text-sm">
                            <i class="bi bi-check-circle-fill text-purple-500"></i>
                            SHU sesuai kontribusi modal
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-500 p-8 md:p-10 text-center">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>

                <div class="relative z-10">
                    <h2 class="text-xl md:text-2xl font-bold text-white mb-2">
                        Siap Bergabung?
                    </h2>
                    <p class="text-white/90 text-sm mb-6 max-w-md mx-auto">
                        Jadilah bagian dari Fanantara dan nikmati berbagai keuntungan sebagai anggota koperasi
                    </p>

                    <a 
                        href="{{ route('register') }}" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-white text-emerald-700 font-bold rounded-xl shadow-lg hover:bg-gray-50 transition-all"
                    >
                        <i class="bi bi-person-plus-fill"></i>
                        Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
