@php
    $appName = \App\Models\SiteSetting::get('app_name', 'Fanantara');
    $appTagline = \App\Models\SiteSetting::get('app_tagline', 'Koperasi Multi Pihak');
@endphp
<!-- Navbar -->
<nav
    class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md shadow-sm transition-all duration-300"
    id="navbar"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 logo-animate">
                <div
                    class="w-12 h-12 rounded-full overflow-hidden transition-all duration-300"
                >
                    <img
                        src="{{ asset('images/logoElemen.png') }}"
                        alt="{{ $appName }}"
                        class="w-full h-full object-contain"
                    />
                </div>
                <div>
                    <h1
                        class="text-xl font-bold text-primary-600 font-brand tracking-wide"
                    >
                        {{ strtoupper($appName) }}
                    </h1>
                    <p class="text-xs text-gray-500">{{ $appTagline }}</p>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" class="nav-link text-gray-700 font-medium">Home</a>
                <a href="{{ route('products') }}" class="nav-link font-medium {{ request()->routeIs('products') ? 'text-emerald-600' : 'text-gray-700' }}">Produk</a>
                <a href="{{ route('articles') }}" class="nav-link font-medium {{ request()->routeIs('articles*') ? 'text-emerald-600' : 'text-gray-700' }}">Artikel</a>
                <a href="{{ route('about') }}" class="nav-link font-medium {{ request()->routeIs('about') ? 'text-emerald-600' : 'text-gray-700' }}">Tentang Kami</a>
                <a
                    href="{{ route('register') }}"
                    class="btn-primary gradient-primary text-white px-6 py-2.5 rounded-full font-semibold flex items-center gap-2"
                >
                    <i class="bi bi-person-plus"></i> Daftar
                </a>
            </div>

            <!-- Mobile Toggle -->
            <button
                class="md:hidden text-2xl text-primary-600 hamburger-icon"
                onclick="toggleMobileMenu()"
                id="menuBtn"
            >
                <i class="bi bi-list" id="menuIcon"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div
        class="hidden md:hidden bg-white border-t border-gray-100 shadow-lg"
        id="mobileMenu"
    >
        <div class="flex flex-col p-4 gap-1 mobile-menu">
            <a
                href="{{ route('home') }}"
                class="text-gray-700 hover:text-primary-600 hover:bg-primary-50 font-medium py-3 px-4 rounded-lg transition-all duration-200"
            >Home</a>
            <a
                href="{{ route('products') }}"
                class="font-medium py-3 px-4 rounded-lg transition-all duration-200 {{ request()->routeIs('products') ? 'text-emerald-600 bg-emerald-50' : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50' }}"
            >Produk</a>
            <a
                href="{{ route('articles') }}"
                class="font-medium py-3 px-4 rounded-lg transition-all duration-200 {{ request()->routeIs('articles*') ? 'text-emerald-600 bg-emerald-50' : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50' }}"
            >Artikel</a>
            <a
                href="{{ route('about') }}"
                class="font-medium py-3 px-4 rounded-lg transition-all duration-200 {{ request()->routeIs('about') ? 'text-emerald-600 bg-emerald-50' : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50' }}"
            >Tentang Kami</a>
            <a
                href="{{ route('register') }}"
                class="gradient-primary text-white px-6 py-3 rounded-full font-semibold text-center mt-2 flex items-center justify-center gap-2"
            >
                <i class="bi bi-person-plus"></i> Daftar
            </a>
        </div>
    </div>
</nav>
