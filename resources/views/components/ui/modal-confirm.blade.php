@props(['id' => 'confirm-modal'])

<div 
    x-data="{ 
        show: false,
        title: '',
        message: '',
        action: null,
        params: null,
        
        open(e) {
            const data = Array.isArray(e.detail) ? e.detail[0] : e.detail
            this.show = true;
            this.title = data.title;
            this.message = data.message;
            this.action = data.action;
            this.params = data.params;
        },
        
        confirm() {
            $wire.call(this.action, this.params);
            this.show = false;
        }
    }"
    x-on:open-confirmation.window="open($event)"
    x-show="show"
    style="display: none;"
    class="fixed inset-0 z-[999] flex items-center justify-center px-4"
>
    {{-- BACKDROP BLUR --}}
    <div 
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="show = false"
    ></div>

    {{-- MODAL BOX --}}
    <div 
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-90 translate-y-4"
        class="relative bg-white rounded-3xl shadow-2xl w-full max-w-xs overflow-hidden"
    >
        {{-- Icon Warning / Info --}}
        <div class="bg-emerald-50 p-6 flex justify-center pb-2">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center animate-bounce-slow">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
        </div>

        <div class="px-6 pb-6 text-center">
            <h3 x-text="title" class="text-lg font-black text-gray-800 mb-2"></h3>
            <p x-text="message" class="text-sm text-gray-500 leading-relaxed"></p>
        </div>

        {{-- BUTTONS --}}
        <div class="flex border-t border-gray-100">
            <button 
                @click="show = false" 
                class="flex-1 py-4 text-sm font-bold text-gray-500 hover:bg-gray-50 transition-colors"
            >
                Batal
            </button>
            <div class="w-px bg-gray-100"></div>
            <button 
                @click="confirm()" 
                class="flex-1 py-4 text-sm font-bold text-red-600 hover:bg-red-50 transition-colors"
            >
                Ya, Hapus
            </button>
        </div>
    </div>
</div>