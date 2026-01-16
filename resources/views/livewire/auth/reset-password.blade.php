<div>
    <h3 class="text-xl font-bold text-gray-900 mb-2">Reset Password</h3>
    <p class="text-sm text-gray-500 mb-6">Silakan buat password baru Anda.</p>

    <form wire:submit.prevent="resetPassword" class="space-y-4">
        <input type="hidden" wire:model="token">

        <div>
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Email</label>
            <input wire:model="email" type="email" readonly 
                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-500 outline-none">
        </div>

        <div>
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Password Baru</label>
            <input wire:model="password" type="password" required 
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
            @error('password') <span class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Konfirmasi Password</label>
            <input wire:model="password_confirmation" type="password" required 
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
        </div>

        <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition-all">
            Perbarui Password
        </button>
    </form>
</div>