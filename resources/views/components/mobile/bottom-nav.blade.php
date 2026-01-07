@props(['active' => 'home'])

<div class="fixed bottom-0 left-0 right-0 z-50 safe-area-bottom">
    <div class="w-full max-w-md mx-auto px-4 pb-4">
        
        {{-- Purple Navigation Bar --}}
        <nav class="bg-purple-700 rounded-2xl shadow-xl shadow-purple-900/30 px-2 py-2">
            
            <div class="grid grid-cols-4 gap-1">
                
                {{-- Beranda --}}
                <a href="{{ route('dashboard') }}" 
                   class="flex flex-col items-center justify-center py-2.5 px-2 rounded-xl transition-all duration-200
                          {{ $active === 'home' 
                              ? 'bg-white/20' 
                              : 'hover:bg-white/10 active:bg-white/15' }}">
                    <x-heroicon-s-home class="w-6 h-6 {{ $active === 'home' ? 'text-amber-400' : 'text-purple-200' }}" />
                    <span class="text-[11px] font-semibold mt-1 {{ $active === 'home' ? 'text-white' : 'text-purple-200' }}">
                        Beranda
                    </span>
                </a>

                {{-- Mutasi --}}
                <a href="{{ route('member.mutation') }}" 
                   class="flex flex-col items-center justify-center py-2.5 px-2 rounded-xl transition-all duration-200
                          {{ $active === 'history' 
                              ? 'bg-white/20' 
                              : 'hover:bg-white/10 active:bg-white/15' }}">
                    {{-- Icon clipboard list untuk mutasi --}}
                    <svg class="w-6 h-6 {{ $active === 'history' ? 'text-amber-400' : 'text-purple-200' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span class="text-[11px] font-semibold mt-1 {{ $active === 'history' ? 'text-white' : 'text-purple-200' }}">
                        Riwayat
                    </span>
                </a>

                {{-- Belanja --}}
                <a href="{{ route('member.marketplace') }}" 
                   class="flex flex-col items-center justify-center py-2.5 px-2 rounded-xl transition-all duration-200
                          {{ $active === 'marketplace' 
                              ? 'bg-white/20' 
                              : 'hover:bg-white/10 active:bg-white/15' }}">
                    <x-heroicon-s-shopping-bag class="w-6 h-6 {{ $active === 'marketplace' ? 'text-amber-400' : 'text-purple-200' }}" />
                    <span class="text-[11px] font-semibold mt-1 {{ $active === 'marketplace' ? 'text-white' : 'text-purple-200' }}">
                        Belanja
                    </span>
                </a>

                {{-- Berita --}}
                <a href="{{ route('member.news') }}" 
                   class="flex flex-col items-center justify-center py-2.5 px-2 rounded-xl transition-all duration-200
                          {{ $active === 'news' 
                              ? 'bg-white/20' 
                              : 'hover:bg-white/10 active:bg-white/15' }}">
                    <x-heroicon-s-newspaper class="w-6 h-6 {{ $active === 'news' ? 'text-amber-400' : 'text-purple-200' }}" />
                    <span class="text-[11px] font-semibold mt-1 {{ $active === 'news' ? 'text-white' : 'text-purple-200' }}">
                        Berita
                    </span>
                </a>

            </div>
        </nav>
        
    </div>
</div>
