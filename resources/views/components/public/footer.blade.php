@php
    $appName = \App\Models\SiteSetting::get('app_name', 'Fanantara');
    $appTagline = \App\Models\SiteSetting::get('app_tagline', 'Koperasi Multi Pihak');
    $appDescription = \App\Models\SiteSetting::get('app_description', 'Koperasi Multi Pihak yang bergerak dalam berbagai bidang usaha untuk kesejahteraan anggota dan masyarakat.');
    $appWhatsapp = \App\Models\SiteSetting::get('contact_whatsapp', '628123456789');
    $appEmail = \App\Models\SiteSetting::get('contact_email', 'info@fanantara.com');
    $appAddress = \App\Models\SiteSetting::get('contact_address', 'Jakarta - Indonesia');
@endphp

<footer class="bg-white border-t border-gray-200 pt-12 pb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-12 gap-6 lg:gap-8 mb-10">
            
            <div class="col-span-2 lg:col-span-4">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-100 shadow-sm">
                        <img
                            src="{{ asset('images/logoElemen.png') }}"
                            alt="{{ $appName }}"
                            class="w-full h-full object-contain"
                        />
                    </div>
                    <div>
                        <p class="text-[10px] text-red-500 font-bold uppercase tracking-widest leading-none mb-1">{{ $appTagline }}</p>
                        <h3 class="text-xl font-black text-blue-900 font-brand tracking-wide leading-none">
                            FANANTARA
                        </h3>
                        <p class="text-[9px] text-blue-600 font-bold mt-1 uppercase">Formas Anugerah Nusantara</p>
                    </div>
                </div>
                
                <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                    {{ $appDescription }}
                </p>

                <div class="space-y-3 mb-6">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-geo-alt-fill text-primary-800 mt-1"></i>
                        <p class="text-xs text-gray-600 leading-relaxed">{{ $appAddress }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="bi bi-envelope-fill text-primary-800"></i>
                        <a href="mailto:{{ $appEmail }}" class="text-xs text-gray-600 hover:text-blue-800 transition">{{ $appEmail }}</a>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="bi bi-whatsapp text-green-600 font-bold"></i>
                        <a href="https://wa.me/{{ $appWhatsapp }}" target="_blank" class="text-xs text-gray-600 hover:text-green-600 font-bold transition">
                            +{{ $appWhatsapp }}
                        </a>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="#" class="w-9 h-9 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center text-black hover:bg-black hover:text-white transition shadow-sm"><i class="bi bi-tiktok"></i></a>
                    <a href="#" class="w-9 h-9 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center text-pink-600 hover:bg-gradient-to-br hover:from-purple-500 hover:to-pink-500 hover:text-white transition shadow-sm"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="w-9 h-9 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center text-red-600 hover:bg-red-600 hover:text-white transition shadow-sm"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <div class="col-span-1 lg:col-span-2">
                <h4 class="text-gray-900 font-bold text-xs uppercase tracking-widest mb-4">Navigasi</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-blue-800 text-sm transition font-medium">Home</a></li>
                    <li><a href="{{ route('products') }}" class="text-gray-500 hover:text-blue-800 text-sm transition font-medium">Produk</a></li>
                    <li><a href="{{ route('articles') }}" class="text-gray-500 hover:text-blue-800 text-sm transition font-medium">Artikel</a></li>
                    <li><a href="{{ route('about') }}" class="text-gray-500 hover:text-blue-800 text-sm transition font-medium">Tentang Kami</a></li>
                </ul>
            </div>

            <div class="col-span-1 lg:col-span-2">
                <h4 class="text-gray-900 font-bold text-xs uppercase tracking-widest mb-4">Akses Anggota</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('login') }}" class="text-gray-500 hover:text-blue-800 text-sm transition font-medium">Masuk Aplikasi</a></li>
                    <li><a href="{{ route('register') }}" class="text-gray-500 hover:text-blue-800 text-sm transition font-medium">Pendaftaran Baru</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-500 hover:text-blue-800 text-sm transition font-medium">Pusat Bantuan</a></li>
                </ul>
            </div>

            <div class="col-span-2 lg:col-span-4 flex flex-col items-center lg:items-end mt-4 lg:mt-0">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 text-center lg:text-right">Terdaftar & Diawasi Oleh</p>
                <div class="flex flex-wrap items-center justify-center lg:justify-end gap-4 mb-4">
                    <img src="{{ asset('images/komdigi-registered.webp') }}" alt="Komdigi" class="h-16 grayscale hover:grayscale-0 transition opacity-80 hover:opacity-100 object-contain" />
                    <img src="{{ asset('images/qrcode-pse.png') }}" alt="QR Code PSE" class="h-16 grayscale hover:grayscale-0 transition opacity-80 hover:opacity-100 object-contain" />
                </div>
                <div class="flex flex-wrap items-center justify-center lg:justify-end gap-4">
                    <img src="{{ asset('images/logo-pse-small.png') }}" alt="PSE" class="h-6 opacity-60 hover:opacity-100 transition object-contain" />
                    <img src="{{ asset('images/logo-zona-integritas.png') }}" alt="Zona Integritas" class="h-6 opacity-60 hover:opacity-100 transition object-contain" />
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-gray-400 text-[11px] font-medium">
                &copy; {{ date('Y') }} <strong>{{ $appName }}</strong>. All rights reserved. 
                <span class="mx-2">|</span> Powered by Fanantara Dev Team
            </p>
            <div class="flex gap-6 text-[11px] font-bold uppercase tracking-tighter">
                <a href="{{ route('privacy') }}" class="text-gray-400 hover:text-blue-800 transition">Privacy Policy</a>
                <a href="{{ route('terms') }}" class="text-gray-400 hover:text-blue-800 transition">Terms & Conditions</a>
            </div>
        </div>
    </div>
</footer>