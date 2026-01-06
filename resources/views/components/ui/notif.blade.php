@props(['id' => 'notif-modal', 'timeout' => 5000])

<div 
    x-data="{ 
        show: false,
        type: 'error',
        title: '',
        message: '',
        timer: null,
        
        open(e) {
            const data = Array.isArray(e.detail) ? e.detail[0] : e.detail;
            this.show = true;
            this.type = data.type || 'error';
            this.title = data.title;
            this.message = data.message;

            // Auto-hide logic
            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                this.show = false;
            }, {{ $timeout }});
        },
        close() {
            this.show = false;
            clearTimeout(this.timer);
        }
    }"
    x-on:notify.window="open($event)"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-[999] flex items-center justify-center px-4"
>
    {{-- BACKDROP --}}
    <div 
        x-show="show"
        x-transition.opacity
        class="absolute inset-0 bg-gray-900/40 backdrop-blur-[2px]"
        @click="close()"
    ></div>

    {{-- MODAL BOX --}}
    <div 
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        class="relative bg-white rounded-3xl shadow-2xl w-full max-w-xs overflow-hidden"
    >
        {{-- DYNAMIC ICON BASED ON TYPE --}}
        <div :class="{
                'bg-red-50 text-red-600': type === 'error',
                'bg-emerald-50 text-emerald-600': type === 'success',
                'bg-amber-50 text-amber-600': type === 'warning'
            }" class="p-6 flex justify-center pb-2">
            
            <div :class="{
                'bg-red-100': type === 'error',
                'bg-emerald-100': type === 'success',
                'bg-amber-100': type === 'warning'
            }" class="w-16 h-16 rounded-full flex items-center justify-center">
                
                {{-- Icon Error --}}
                <template x-if="type === 'error'">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </template>
                {{-- Icon Success --}}
                <template x-if="type === 'success'">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </template>
            </div>
        </div>

        <div class="px-6 pb-6 text-center">
            <h3 x-text="title" class="text-lg font-black text-gray-800 mb-1"></h3>
            <p x-text="message" class="text-sm text-gray-500 leading-relaxed"></p>
        </div>

        <button 
            @click="close()" 
            class="w-full py-4 text-sm font-bold border-t border-gray-100 text-gray-600 hover:bg-gray-50 transition-colors"
        >
            Tutup
        </button>
    </div>
</div>