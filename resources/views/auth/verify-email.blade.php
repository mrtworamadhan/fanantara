<x-layouts.auth title="Verifikasi Email">
    <div class="text-center">
        <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        </div>
        
        <h3 class="text-xl font-bold text-gray-900 mb-2">Cek Email Anda</h3>
        <p class="text-sm text-gray-500 leading-relaxed mb-6">
            Kami telah mengirimkan link verifikasi ke email Anda. Silakan klik link tersebut untuk mengaktifkan akun.
        </p>

        @if (session('message'))
            <div class="mb-6 p-3 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-xl border border-emerald-100">
                {{ session('message') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition-all">
                Kirim Ulang Email
            </button>
        </form>

        <a href="{{ route('logout') }}" class="block mt-6 text-xs font-bold text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">
            Keluar / Ganti Akun
        </a>
    </div>
</x-layouts.auth>