<div class="p-6 pb-24">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Lengkapi Profil</h1>
        <p class="text-gray-500 text-sm mt-1">Data Anda dibutuhkan untuk validasi keanggotaan koperasi.</p>
        
        <div class="mt-4 flex gap-2">
            <div class="h-1.5 flex-1 bg-primary-600 rounded-full"></div> <div class="h-1.5 flex-1 bg-gray-200 rounded-full"></div>   </div>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        
        <div class="bg-primary-50 border border-primary-100 rounded-xl p-4 text-center border-dashed border-2">
            <div class="space-y-2">
                @if ($ktp_image)
                    <img src="{{ $ktp_image->temporaryUrl() }}" class="h-32 mx-auto rounded-lg object-cover">
                @else
                    <div class="h-12 w-12 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Upload Foto KTP</p>
                    <p class="text-xs text-gray-400">Pastikan tulisan terbaca jelas</p>
                @endif
                
                <input type="file" wire:model="ktp_image" id="ktp_image" class="hidden">
                <label for="ktp_image" class="block w-full py-2 px-4 border border-transparent rounded-lg text-sm font-medium text-primary-700 bg-primary-100 hover:bg-primary-200 cursor-pointer transition-colors">
                    Pilih File
                </label>
            </div>
            @error('ktp_image') <span class="text-red-500 text-xs block mt-2">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk Kependudukan (NIK)</label>
            <input type="text" wire:model="nik" inputmode="numeric" placeholder="16 digit NIK" maxlength="16"
                class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm py-3 px-4 text-lg tracking-wide">
            @error('nik') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
            <div class="flex rounded-md shadow-sm">
                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                    +62
                </span>
                <input type="tel" wire:model="phone" placeholder="8123xxxx"
                    class="flex-1 w-full rounded-r-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 py-3 px-4">
            </div>
            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
            <textarea wire:model="address" rows="3" placeholder="Nama Jalan, No. Rumah, RT/RW..."
                class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm py-3 px-4"></textarea>
            @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-100 max-w-md mx-auto">
            <button type="submit" wire:loading.attr="disabled"
                class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-base font-semibold text-white bg-secondary-600 hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500 transition-all">
                <span wire:loading.remove>Lanjut ke Pembayaran</span>
                <span wire:loading>Menyimpan Data...</span>
                
                <svg wire:loading.remove class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
            </button>
        </div>
    </form>
</div>