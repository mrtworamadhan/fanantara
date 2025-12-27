@php
    $appName = \App\Models\SiteSetting::get('app_name', 'Fanantara');
    $appTagline = \App\Models\SiteSetting::get('app_tagline', 'Koperasi Multi Pihak');
    $appDescription = \App\Models\SiteSetting::get('app_description', 'Koperasi Multi Pihak yang bergerak dalam berbagai bidang usaha untuk kesejahteraan anggota dan masyarakat.');
@endphp
<!-- Footer -->
<footer class="bg-white border-t border-gray-200 pt-12 pb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Footer Content -->
        <div class="grid grid-cols-2 lg:grid-cols-12 gap-6 lg:gap-8 mb-10">
            <!-- Brand -->
            <div class="col-span-2 lg:col-span-4">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full overflow-hidden">
                        <img
                            src="{{ asset('images/logoElemen.png') }}"
                            alt="{{ $appName }}"
                            class="w-full h-full object-contain"
                        />
                    </div>

                    <div>
                        <p class="text-xs text-red-500">{{ $appTagline }}</p>
                        <h3
                            class="text-xl font-bold text-blue-800 font-brand tracking-wide"
                        >
                            FANANTARA
                        </h3>
                        <p class="text-xs text-primary-500">FORMAS ANUGERAH NUSANTARA</p>
                        
                    </div>
                </div>
                <p class="text-gray-600 text-sm mb-4">
                    {{ $appDescription }}
                </p>
                <!-- Social Media -->
                <div class="flex gap-3">
                    <a
                        href="#"
                        class="w-9 h-9 bg-gray-100 rounded-full flex items-center justify-center text-black hover:bg-black hover:text-white transition"
                    ><i class="bi bi-tiktok"></i></a>
                    <a
                        href="#"
                        class="w-9 h-9 bg-gray-100 rounded-full flex items-center justify-center text-pink-600 hover:bg-gradient-to-br hover:from-purple-500 hover:to-pink-500 hover:text-white transition"
                    ><i class="bi bi-instagram"></i></a>
                    <a
                        href="#"
                        class="w-9 h-9 bg-gray-100 rounded-full flex items-center justify-center text-red-600 hover:bg-red-600 hover:text-white transition"
                    ><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <!-- NAVIGASI -->
            <div class="col-span-1 lg:col-span-3">
                <h4 class="text-gray-800 font-semibold text-sm mb-3">NAVIGASI</h4>
                <ul class="space-y-2">
                    <li>
                        <a
                            href="{{ route('home') }}"
                            class="text-gray-600 hover:text-primary-600 text-sm transition"
                        >Home</a>
                    </li>
                    <li>
                        <a
                            href="{{ route('products') }}"
                            class="text-gray-600 hover:text-primary-600 text-sm transition"
                        >Produk</a>
                    </li>
                    <li>
                        <a
                            href="{{ route('articles') }}"
                            class="text-gray-600 hover:text-primary-600 text-sm transition"
                        >Artikel</a>
                    </li>
                    <li>
                        <a
                            href="{{ route('about') }}"
                            class="text-gray-600 hover:text-primary-600 text-sm transition"
                        >Tentang Kami</a>
                    </li>
                </ul>
            </div>

            <!-- LINK -->
            <div class="col-span-1 lg:col-span-2">
                <h4 class="text-gray-800 font-semibold text-sm mb-3">LINK</h4>
                <ul class="space-y-2">
                    <li>
                        <a
                            href="{{ route('login') }}"
                            class="text-gray-600 hover:text-primary-600 text-sm transition"
                        >Masuk</a>
                    </li>
                    <li>
                        <a
                            href="{{ route('register') }}"
                            class="text-gray-600 hover:text-primary-600 text-sm transition"
                        >Daftar</a>
                    </li>
                    <li>
                        <a
                            href="{{ route('adart') }}"
                            class="text-gray-600 hover:text-primary-600 text-sm transition"
                        >AD/ART</a>
                    </li>
                    <li>
                        <a
                            href="{{ route('contact') }}"
                            class="text-gray-600 hover:text-primary-600 text-sm transition"
                        >Kontak Kami</a>
                    </li>
                </ul>
            </div>

            <!-- Logos -->
            <div
                class="col-span-2 lg:col-span-3 flex flex-col items-center mt-4 lg:mt-0"
            >
                <div class="flex flex-wrap items-center justify-center gap-4 mb-3">
                    <img
                        src="{{ asset('images/komdigi-registered.webp') }}"
                        alt="Komdigi Registered"
                        class="h-20 object-contain"
                    />
                    <img
                        src="{{ asset('images/qrcode-pse.png') }}"
                        alt="QR Code PSE"
                        class="h-20 object-contain"
                    />
                </div>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <img
                        src="{{ asset('images/logo-pse-small.png') }}"
                        alt="Logo PSE"
                        class="h-7 object-contain"
                    />
                    <img
                        src="{{ asset('images/logo-zona-integritas.png') }}"
                        alt="Logo Zona Integritas"
                        class="h-7 object-contain"
                    />
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div
            class="border-t border-gray-200 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4"
        >
            <p class="text-gray-600 text-sm">
                &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
            </p>
            <div class="flex gap-4 text-sm">
                <a href="{{ route('privacy') }}" class="text-gray-600 hover:text-primary-600 transition">Privacy Policy</a>
                <a href="{{ route('terms') }}" class="text-gray-600 hover:text-primary-600 transition">Terms & Conditions</a>
            </div>
        </div>
    </div>
</footer>
