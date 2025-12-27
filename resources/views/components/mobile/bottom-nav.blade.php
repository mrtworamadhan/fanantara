@props(['active' => 'home'])

<div class="fixed bottom-4 left-0 right-0 z-50 flex justify-center">
    <div class="w-full max-w-md px-3">
        <div
            class="bg-emerald-800 rounded-3xl px-6 py-4
                   border border-white/10
                   shadow-[0_20px_40px_rgba(0,0,0,0.15)]">

            <div class="grid grid-cols-4 gap-2 text-center">

                <a href="{{ route('dashboard') }}" class="flex justify-center">
                    <div
                        class="flex flex-col items-center px-3 py-2 rounded-xl
                               transition-all duration-300
                               {{ $active === 'home'
                                    ? 'bg-emerald-700/40'
                                    : 'hover:bg-white/10'
                               }}">
                        <x-heroicon-o-home
                            class="w-6 h-6 {{ $active === 'home' ? 'text-amber-400' : 'text-gray-400' }}"
                        />
                        <span class="text-[11px] mt-1 {{ $active === 'home' ? 'text-amber-400' : 'text-gray-400' }}">
                            Beranda
                        </span>
                    </div>
                </a>

                <a href="{{ route('member.mutation') }}" class="flex justify-center">
                    <div
                        class="flex flex-col items-center px-3 py-2 rounded-xl
                               transition-all duration-300
                               {{ $active === 'history'
                                    ? 'bg-emerald-700/40'
                                    : 'hover:bg-white/10'
                               }}">
                        <x-heroicon-o-receipt-refund
                            class="w-6 h-6 {{ $active === 'history' ? 'text-amber-400' : 'text-gray-400' }}"
                        />
                        <span class="text-[11px] mt-1 {{ $active === 'history' ? 'text-amber-400' : 'text-gray-400' }}">
                            Riwayat
                        </span>
                    </div>
                </a>

                <a href="{{ route('member.marketplace') }}" class="flex justify-center">
                    <div
                        class="flex flex-col items-center px-3 py-2 rounded-xl
                               transition-all duration-300
                               {{ $active === 'shop'
                                    ? 'bg-emerald-700/40'
                                    : 'hover:bg-white/10'
                               }}">
                        <x-heroicon-o-building-storefront
                            class="w-6 h-6 {{ $active === 'marketplace' ? 'text-amber-400' : 'text-gray-400' }}"
                        />
                        <span class="text-[11px] mt-1 {{ $active === 'marketplace' ? 'text-amber-400' : 'text-gray-400' }}">
                            Shop
                        </span>
                    </div>
                </a>

                <a href="{{ route('member.news') }}" class="flex justify-center">
                    <div
                        class="flex flex-col items-center px-3 py-2 rounded-xl
                               transition-all duration-300
                               {{ $active === 'news'
                                    ? 'bg-emerald-700/40'
                                    : 'hover:bg-white/10'
                               }}">
                        <x-heroicon-o-newspaper
                            class="w-6 h-6 {{ $active === 'news' ? 'text-amber-400' : 'text-gray-400' }}"
                        />
                        <span class="text-[11px] mt-1 {{ $active === 'news' ? 'text-amber-400' : 'text-gray-400' }}">
                            News
                        </span>
                    </div>
                </a>

            </div>
        </div>
    </div>
</div>
