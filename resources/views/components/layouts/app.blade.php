<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <title>{{ $title ?? 'Fanantara Member' }}</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    @filamentStyles

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>

</head>

<body class="bg-white font-sans antialiased text-gray-900 flex justify-center min-h-screen">
    
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