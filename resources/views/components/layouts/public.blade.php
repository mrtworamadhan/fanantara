@php
    $appName = \App\Models\SiteSetting::get('app_name', 'Fanantara');
    $appTagline = \App\Models\SiteSetting::get('app_tagline', 'Koperasi Multi Pihaka');
    $appDescription = \App\Models\SiteSetting::get('app_description', 'Koperasi Multi Pihak yang bergerak dalam berbagai bidang usaha untuk kesejahteraan anggota dan masyarakat.');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? $appName }} - {{ $appTagline }}</title>
    <meta
        name="description"
        content="{{ $appDescription }}"
    />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    />

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />

    <!-- Alpine.js -->
    <script
        defer
        src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
    ></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: "#f0fdf4",
                            100: "#dcfce7",
                            200: "#bbf7d0",
                            300: "#86efac",
                            400: "#4ade80",
                            500: "#22c55e",
                            600: "#16a34a",
                            700: "#15803d",
                            800: "#166534",
                            900: "#14532d",
                            950: "#052e16",
                        },
                        secondary: {
                            50: "#faf5ff",
                            100: "#f3e8ff",
                            200: "#e9d5ff",
                            300: "#d8b4fe",
                            400: "#c084fc",
                            500: "#a855f7",
                            600: "#9333ea",
                            700: "#7c3aed",
                            800: "#6b21a8",
                            900: "#581c87",
                            950: "#3b0764",
                        },
                    },
                    fontFamily: {
                        sans: ["Plus Jakarta Sans", "sans-serif"],
                    },
                },
            },
        };
    </script>
    <style>
        /* Hide x-cloak elements until Alpine.js is ready */
        [x-cloak] {
            display: none !important;
        }

        .gradient-primary {
            background: linear-gradient(
                135deg,
                #22c55e 0%,
                #16a34a 50%,
                #15803d 100%
            );
        }
        .gradient-secondary {
            background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);
        }
        .gradient-mixed {
            background: linear-gradient(135deg, #22c55e 0%, #9333ea 100%);
        }
        .text-gradient {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .text-gradient-mixed {
            background: linear-gradient(135deg, #22c55e, #9333ea);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(34, 197, 94, 0.15);
        }
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .banner-bg {
            background: linear-gradient(
                    135deg,
                    rgba(34, 197, 94, 0.85),
                    rgba(147, 51, 234, 0.75)
                ),
                url("{{ asset('images/banner.webp') }}") center/cover no-repeat;
        }
        /* Navbar Animations */
        .nav-link {
            position: relative;
            padding: 0.5rem 0;
        }
        .nav-link::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #22c55e, #9333ea);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .nav-link:hover {
            color: #22c55e;
        }
        .logo-animate {
            transition: all 0.3s ease;
        }
        .logo-animate:hover {
            transform: scale(1.05);
        }
        .logo-animate:hover img {
            transform: rotate(5deg);
        }
        .logo-animate img {
            transition: transform 0.3s ease;
        }
        .btn-primary {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .btn-primary::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.3),
                transparent
            );
            transition: left 0.5s ease;
        }
        .btn-primary:hover::before {
            left: 100%;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(34, 197, 94, 0.3);
        }
        .mobile-menu {
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .hamburger-icon {
            transition: all 0.3s ease;
        }
        .hamburger-icon:hover {
            color: #4b5563;
            transform: scale(1.1);
        }
        /* Banner Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes pulse {
            0%,
            100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        @keyframes float {
            0%,
            100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease forwards;
        }
        .animate-fade-in-down {
            animation: fadeInDown 0.8s ease forwards;
        }
        .animate-pulse-slow {
            animation: pulse 3s ease-in-out infinite;
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
        .delay-100 {
            animation-delay: 0.1s;
        }
        .delay-200 {
            animation-delay: 0.2s;
        }
        .delay-300 {
            animation-delay: 0.3s;
        }
        .delay-400 {
            animation-delay: 0.4s;
        }
        .banner-content {
            opacity: 0;
        }
        .banner-icon {
            position: absolute;
            opacity: 0.1;
            font-size: 8rem;
            color: white;
            z-index: 0;
            pointer-events: none;
        }
        /* Feature Card */
        .feature-card {
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .feature-card:hover .feature-icon {
            transform: scale(1.1);
        }
        .feature-icon {
            transition: all 0.3s ease;
        }
        /* Scroll Animation */
        .scroll-animate {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        .scroll-animate.animate-visible {
            opacity: 1;
            transform: translateY(0);
        }
        .scroll-animate.animate-left {
            transform: translateX(-40px);
        }
        .scroll-animate.animate-left.animate-visible {
            transform: translateX(0);
        }
        .scroll-animate.animate-right {
            transform: translateX(40px);
        }
        .scroll-animate.animate-right.animate-visible {
            transform: translateX(0);
        }
        .scroll-animate.animate-scale {
            transform: scale(0.9);
        }
        .scroll-animate.animate-scale.animate-visible {
            transform: scale(1);
        }
        /* Scroll Up Button */
        .scroll-up-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: white;
            color: #6b7280;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            cursor: pointer;
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        .scroll-up-btn:hover {
            background: #f9fafb;
            color: #374151;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }
        .scroll-up-btn.visible {
            opacity: 0.8;
            visibility: visible;
            transform: translateY(0);
        }
        .scroll-up-btn.visible:hover {
            opacity: 1;
        }
    </style>
    @livewireStyles
</head>
<body class="font-sans bg-slate-50 text-gray-800">
    <!-- Navbar -->
    <x-public.navbar />

    <!-- Main Content -->
    {{ $slot }}

    <!-- Footer -->
    <x-public.footer />

    <!-- Scroll Up Button -->
    <button
        id="scrollUpBtn"
        class="scroll-up-btn"
        onclick="scrollToTop()"
        aria-label="Scroll ke atas"
    >
        <i class="bi bi-arrow-up"></i>
    </button>

    <script>
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const menu = document.getElementById("mobileMenu");
            const icon = document.getElementById("menuIcon");
            menu.classList.toggle("hidden");
            if (menu.classList.contains("hidden")) {
                icon.className = "bi bi-list";
            } else {
                icon.className = "bi bi-x-lg";
            }
        }

        // Navbar Scroll Effect
        window.addEventListener("scroll", function () {
            const navbar = document.getElementById("navbar");
            if (window.scrollY > 50) {
                navbar.classList.add("shadow-lg", "bg-white");
                navbar.classList.remove("bg-white/95");
            } else {
                navbar.classList.remove("shadow-lg", "bg-white");
                navbar.classList.add("bg-white/95");
            }
        });

        // Scroll Animation with Intersection Observer
        const observerOptions = {
            root: null,
            rootMargin: "0px",
            threshold: 0.1,
        };

        const scrollObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("animate-visible");
                }
            });
        }, observerOptions);

        // Observe all scroll-animate elements
        document.querySelectorAll(".scroll-animate").forEach((el) => {
            scrollObserver.observe(el);
        });

        // Scroll Up Button Logic
        const scrollUpBtn = document.getElementById("scrollUpBtn");
        let scrollTimeout;
        let isScrolling = false;

        function showScrollBtn() {
            if (window.scrollY > 300) {
                scrollUpBtn.classList.add("visible");
                isScrolling = true;

                // Clear existing timeout
                clearTimeout(scrollTimeout);

                // Hide after 2 seconds of no scrolling
                scrollTimeout = setTimeout(() => {
                    scrollUpBtn.classList.remove("visible");
                    isScrolling = false;
                }, 2000);
            } else {
                scrollUpBtn.classList.remove("visible");
            }
        }

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: "smooth",
            });
        }

        window.addEventListener("scroll", showScrollBtn);
    </script>
    @livewireScripts
</body>
</html>
