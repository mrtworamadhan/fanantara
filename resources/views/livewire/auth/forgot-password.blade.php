<div>
    <h3 class="text-xl font-bold text-gray-900 mb-2">Lupa Password?</h3>
    <p class="text-sm text-gray-500 mb-6">Masukkan email Anda untuk menerima link reset password.</p>

    @if (session('status'))
        <div class="mb-4 p-3 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-xl border border-emerald-100">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit.prevent="sendResetLink" class="space-y-4">
        <div>
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Email</label>
            <input wire:model="email" type="email" required 
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none">
            @error('email') <span class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</span> @enderror
        </div>

        <button type="submit" wire:loading.attr="disabled"
            class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition-all flex justify-center items-center">
            <span wire:loading.remove>Kirim Link Reset</span>
            <span wire:loading>Memproses...</span>
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-xs font-bold text-emerald-600 uppercase tracking-widest hover:underline">Kembali ke Login</a>
    </div>
</div>