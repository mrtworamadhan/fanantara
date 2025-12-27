<div>
    <!-- Hero Header - Compact Mobile First -->
    <section class="pt-24 pb-8 md:pt-28 md:pb-12 bg-gradient-to-br from-purple-50 via-white to-emerald-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-purple-100 text-purple-700 text-sm font-bold rounded-full mb-4">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                        <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
                    </svg>
                    Berita & Informasi
                </span>
                <h1 class="text-2xl md:text-4xl font-black text-gray-900 mb-3">
                    Artikel <span class="text-gradient">Terbaru</span>
                </h1>
                <p class="text-gray-600 text-sm md:text-base max-w-xl mx-auto">
                    Berita terbaru dan informasi penting dari Koperasi Fanantara
                </p>
            </div>
        </div>
    </section>

    <!-- Articles Section -->
    <section class="py-8 md:py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-6 bg-purple-500 rounded-full"></div>
                    <h2 class="text-lg md:text-xl font-bold text-gray-800">Semua Artikel</h2>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                    {{ $articles->total() }} Artikel
                </span>
            </div>

            <!-- Articles Grid -->
            @if($articles->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($articles as $article)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
                        
                        <a 
                            href="{{ route('articles.show', $article->slug) }}"
                            class="flex gap-4 p-4"
                        >
                            <div class="w-24 h-24 shrink-0 rounded-xl overflow-hidden bg-gray-100">
                                <img 
                                    src="{{ $article->thumbnail 
                                        ? asset('storage/'.$article->thumbnail) 
                                        : 'https://placehold.co/300x300?text=News' 
                                    }}"
                                    alt="{{ $article->title }}"
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
                        </a>

                        <div class="flex items-center justify-between px-4 pb-3">
                            <a 
                                href="{{ route('articles.show', $article->slug) }}"
                                class="text-emerald-600 text-[11px] font-black uppercase tracking-tight inline-flex items-center gap-1"
                            >
                                Baca Detail
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>

                            <button
                                onclick="shareArticle('{{ addslashes($article->title) }}', '{{ route('articles.show', $article->slug) }}')"
                                class="p-2 rounded-full bg-gray-100 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 transition-all"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $articles->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-newspaper text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Artikel</h3>
                    <p class="text-gray-500 text-sm">Artikel akan segera tersedia</p>
                </div>
            @endif
        </div>
    </section>
</div>
