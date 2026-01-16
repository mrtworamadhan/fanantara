<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Auth' }} - Fanantara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center p-6">
        <div class="mb-8 text-center">
            <img src="{{ asset('images/logoElemen.png') }}" alt="Logo" class="w-16 h-16 mx-auto mb-4">
            <h2 class="text-2xl font-black text-emerald-800 uppercase tracking-tight">Fanantara</h2>
        </div>

        <div class="w-full max-w-md bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8">
            {{ $slot }}
        </div>

        <p class="mt-8 text-xs text-gray-400 text-center">
            &copy; {{ date('Y') }} Koperasi Multi Pihak Fanantara
        </p>
    </div>
</body>
</html>