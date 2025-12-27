<div>
    <!-- Back Button & Header -->
    <section class="pt-24 pb-6 md:pt-28 md:pb-8 bg-gradient-to-br from-purple-50 via-white to-emerald-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Link -->
            <a 
                href="{{ route('articles') }}" 
                class="inline-flex items-center gap-2 text-gray-500 hover:text-emerald-600 text-sm font-medium mb-6 transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Artikel
            </a>
            
            <!-- Meta Info -->
            <div class="flex items-center gap-3 mb-4">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                    {{ $article->created_at->diffForHumans() }}
                </span>
                @if($article->category)
                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full uppercase">
                    {{ $article->category }}
                </span>
                @endif
            </div>
            
            <!-- Title -->
            <h1 class="text-2xl md:text-3xl lg:text-4xl font-black text-gray-900 leading-tight">
                {{ $article->title }}
            </h1>
        </div>
    </section>

    <!-- Article Content -->
    <section class="py-8 md:py-12 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Featured Image -->
            @if($article->thumbnail)
            <div class="aspect-video rounded-2xl overflow-hidden mb-8 shadow-lg">
                <img 
                    src="{{ asset('storage/' . $article->thumbnail) }}" 
                    alt="{{ $article->title }}"
                    class="w-full h-full object-cover"
                >
            </div>
            @endif

            <!-- Content -->
            <article class="prose prose-sm md:prose-base lg:prose-lg max-w-none 
                prose-headings:font-bold prose-headings:text-gray-900 
                prose-p:text-gray-600 prose-p:leading-relaxed 
                prose-a:text-emerald-600 prose-a:no-underline hover:prose-a:underline
                prose-img:rounded-xl prose-img:shadow-md">
                {!! $article->content !!}
            </article>

            <!-- Share Section -->
            <div class="mt-10 pt-6 border-t border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Bagikan Artikel</p>
                <div class="flex flex-wrap gap-2">
                    <a 
                        href="https://wa.me/?text={{ urlencode($article->title . ' ' . request()->url()) }}" 
                        target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white text-sm font-bold rounded-xl transition-colors"
                    >
                        <i class="bi bi-whatsapp"></i>
                        WhatsApp
                    </a>
                    <a 
                        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                        target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-colors"
                    >
                        <i class="bi bi-facebook"></i>
                        Facebook
                    </a>
                    <a 
                        href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(request()->url()) }}" 
                        target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-colors"
                    >
                        <i class="bi bi-twitter-x"></i>
                        Twitter
                    </a>
                    <button 
                        onclick="navigator.clipboard.writeText('{{ request()->url() }}'); this.innerHTML='<i class=\'bi bi-check\'></i> Disalin!'; setTimeout(() => this.innerHTML='<i class=\'bi bi-link-45deg\'></i> Salin Link', 2000)"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl transition-colors"
                    >
                        <i class="bi bi-link-45deg"></i>
                        Salin Link
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Articles -->
    @if($relatedArticles->count() > 0)
    <section class="py-10 md:py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex items-center gap-2 mb-6">
                <div class="w-1 h-6 bg-emerald-500 rounded-full"></div>
                <h2 class="text-lg md:text-xl font-bold text-gray-800">Artikel Terkait</h2>
            </div>
            
            <!-- Related Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($relatedArticles as $related)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
                    <a 
                        href="{{ route('articles.show', $related->slug) }}"
                        class="flex gap-4 p-4"
                    >
                        <div class="w-20 h-20 shrink-0 rounded-xl overflow-hidden bg-gray-100">
                            <img 
                                src="{{ $related->thumbnail 
                                    ? asset('storage/'.$related->thumbnail) 
                                    : 'https://placehold.co/300x300?text=News' 
                                }}"
                                alt="{{ $related->title }}"
                                class="w-full h-full object-cover"
                            >
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">
                                {{ $related->created_at->diffForHumans() }}
                            </p>
                            <h3 class="text-sm font-bold text-gray-900 leading-snug line-clamp-2 hover:text-emerald-600 transition-colors">
                                {{ $related->title }}
                            </h3>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
