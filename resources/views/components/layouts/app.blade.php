<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <title>{{ $title ?? 'Fanantara Member' }}</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    />

    @filamentStyles

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        
        #nprogress .bar { background: #D4AF37 !important; height: 3px !important; box-shadow: 0 0 10px #D4AF37; }

        .animate-bounce-soft {
            animation: bounceSoft 1.4s ease-in-out infinite;
        }

            @keyframes bounceSoft {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-6px);
            }
        }


        
        #global-splash {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition: opacity 0.3s ease-out, visibility 0.3s ease-out; 
        }

        body.page-ready #global-splash {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
    </style>

</head>

<body class="bg-slate-50 font-sans antialiased text-gray-900 flex justify-center min-h-screen">
    <div id="global-splash" class="fixed inset-0 z-[9999] bg-white/80 dark:bg-slate-900/80 backdrop-blur-md flex items-center justify-center transition-opacity duration-700">
        <div class="bg-white p-8 rounded-2xl shadow-2xl flex flex-col items-center animate-bounce-slight">
            <img src="{{ asset('images/logoElemen.png') }}" class="w-20 h-20 mb-4 animate-spin-slow object-contain" >            
            <span class="text-xs font-bold text-black uppercase tracking-[0.2em] animate-pulse">Memuat...</span>
        </div>
    </div>

    <script>
        const SPLASH_DELAY = 2000; 

        function hideSplashWithDelay() {
            setTimeout(() => {
                document.body.classList.add('page-ready');
            }, SPLASH_DELAY);
        }

        document.addEventListener("DOMContentLoaded", () => {
            hideSplashWithDelay();
        });

        document.addEventListener('livewire:navigating', () => {
            document.body.classList.remove('page-ready');
        });

        document.addEventListener('livewire:navigated', () => {
            applyTheme();
            hideSplashWithDelay();
        });
    </script>

    <main class="w-full max-w-md bg-white min-h-screen relative flex flex-col overflow-hidden">
        
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            {{ $slot }}
        </div>
    </main>
    @livewireScripts

    @filamentScripts

    <div
        x-data="{
            show: false,
            type: 'success',
            title: '',
            message: '',
            timer: null,

            open(e) {
                const data = Array.isArray(e.detail) ? e.detail[0] : e.detail

                this.type = data.type ?? 'success'
                this.title = data.title ?? ''
                this.message = data.message ?? ''
                this.show = true

                clearTimeout(this.timer)
                this.timer = setTimeout(() => this.show = false, 2200)
            }
        }"
        x-on:cart-modal.window="open($event)"
        x-show="show"
        x-transition.opacity
        style="display: none"
        class="fixed inset-0 z-[999] flex items-center justify-center px-4"
    >
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

        <div
            x-transition.scale
            class="relative bg-white w-full max-w-xs rounded-3xl shadow-2xl p-6 text-center"
        >
            <div
                :class="{
                    'bg-emerald-100 text-emerald-600': type === 'success',
                    'bg-amber-100 text-amber-600': type === 'warning',
                    'bg-red-100 text-red-600': type === 'error'
                }"
                class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4"
            >
                <template x-if="type === 'success'">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                            d="M5 13l4 4L19 7"/>
                    </svg>
                </template>

                <template x-if="type === 'warning'">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-7 4h14l-7-14z"/>
                    </svg>
                </template>

                <template x-if="type === 'error'">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </template>
            </div>

            <h3 class="text-lg font-black text-gray-800" x-text="title"></h3>
            <p class="text-sm text-gray-500 mt-1" x-text="message"></p>
        </div>
    </div>

</body>
<script>
    async function shareContent(title, text, url) {
        if (!title) title = document.title;
        if (!url) url = window.location.href;

        if (navigator.share) {
            try {
                await navigator.share({ title: title, text: text, url: url });
                return;
            } catch (err) {
                console.log('Share native dibatalkan');
            }
        }

        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(url);
                alert('Link berhasil disalin! Siap dibagikan.');
            } else {
                const textArea = document.createElement("textarea");
                textArea.value = url;
                textArea.style.position = "fixed";
                textArea.style.left = "-9999px";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('Link berhasil disalin! Siap dibagikan.');
            }
        } catch (err) {
            prompt('Silakan salin link manual:', url);
        }
    }
</script>

</html>