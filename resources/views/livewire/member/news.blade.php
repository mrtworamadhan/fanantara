<div class="h-screen bg-gray-50 flex flex-col relative overflow-hidden font-sans" 
     x-data="{ showModal: false }" 
     @open-article-modal.window="showModal = true">
    
     <div class="bg-emerald-700 px-5 pt-5 pb-4 shadow-lg z-40 flex-none text-white">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="p-2 rounded-full bg-white/10 backdrop-blur-sm active:scale-90 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-xl font-bold tracking-tight">Kabar Koperasi</h1>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto no-scrollbar bg-gradient-to-b from-emerald-700 via-gray-50 to-white px-4 pt-4 pb-24 mb-6">
        <div class="space-y-3 animate-fade-in">
            @forelse($articles as $article)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm active:scale-[0.98] transition-all relative">
                    
                    <div 
                        wire:click.prevent="showArticle({{ $article->id }})"
                        class="flex gap-4 p-4 cursor-pointer"
                    >
                        <div class="w-24 h-24 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100">
                            <img 
                                src="{{ $article->thumbnail 
                                    ? asset('storage/'.$article->thumbnail) 
                                    : 'https://placehold.co/300x300?text=News' 
                                }}"
                                class="w-full h-full object-cover"
                            >
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">
                                {{ $article->created_at->diffForHumans() }}
                            </p>

                            <h2 class="text-sm font-bold text-gray-900 leading-snug line-clamp-3 hover:text-emerald-600 transition-colors">
                                {{ $article->title }}
                            </h2>
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-4 pb-3">
                        <button 
                            wire:click="showArticle({{ $article->id }})"
                            class="text-emerald-600 text-[11px] font-black uppercase tracking-tight inline-flex items-center gap-1"
                        >
                            Baca Detail
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </button>

                        <button
                            @click.stop="shareContent(
                                '{{ addslashes($article->title) }}',
                                'Cek berita terbaru dari Koperasi Fanantara',
                                '{{ route('news.detail', $article->id) }}'
                            )"
                            class="p-2 rounded-full bg-gray-100 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 transition-all active:scale-90"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12
                                    c0-.482-.114-.938-.316-1.342m0 2.684
                                    a3 3 0 110-2.684m0 2.684l6.632 3.316
                                    m-6.632-6l6.632-3.316m0 0
                                    a3 3 0 105.367-2.684
                                    a3 3 0 00-5.367 2.684
                                    zm0 9.316a3 3 0 105.368 2.684
                                    a3 3 0 00-5.368-2.684z"/>
                            </svg>
                        </button>

                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-20 opacity-40 text-center">
                    <x-heroicon-o-newspaper class="w-16 h-16 text-emerald-200 mb-4" />
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">
                        Belum ada kabar terbaru
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <div x-show="showModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         class="fixed inset-0 z-[100] flex flex-col bg-white overflow-hidden" 
         style="display: none;">
        
        @if($selectedArticle)
            <div class="relative h-72 flex-none shadow-lg">
                <img src="{{ $selectedArticle->thumbnail ? asset('storage/'.$selectedArticle->thumbnail) : 'https://placehold.co/600x400?text=Kabar+Koperasi' }}" 
                     class="w-full h-full object-cover">
                
                <button @click="showModal = false" 
                        wire:click="closeArticle"
                        class="absolute top-10 left-5 p-2 bg-black/40 backdrop-blur-md rounded-full text-white active:scale-90 transition-all border border-white/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto no-scrollbar px-6 py-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-[10px] font-black bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full uppercase tracking-widest">
                        INFO TERKINI
                    </span>
                    <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $selectedArticle->created_at->translatedFormat('d M Y') }}</span>
                </div>

                <h1 class="text-2xl font-black text-gray-900 leading-tight mb-6">
                    {{ $selectedArticle->title }}
                </h1>

                <div class="prose prose-sm prose-emerald max-w-none text-gray-600 leading-relaxed pb-20">
                    {!! $selectedArticle->content !!}
                </div>
            </div>

            <div class="p-5 bg-white border-t border-gray-100 flex-none shadow-[0_-4px_10px_rgba(0,0,0,0.03)]">
                <button 
                    @click.stop="shareContent(
                                '{{ addslashes($article->title) }}',
                                'Cek berita terbaru dari Koperasi Fanantara',
                                '{{ route('news.detail', $article->id) }}'
                            )"
                    class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-black text-sm shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 active:scale-95 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                    </svg>
                    BAGIKAN KABAR INI
                </button>
            </div>
        @endif
    </div>

    <x-mobile.bottom-nav active="news" />

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</div>