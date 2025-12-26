<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <title>{{ $title ?? 'Fanantara Member' }}</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

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