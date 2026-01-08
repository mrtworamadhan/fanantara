<div class="min-h-screen bg-gray-50 flex flex-col font-sans">
    {{-- Header Purple --}}
    <div class="bg-purple-700 px-5 pt-5 pb-4 shadow-lg z-40 flex-none">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="p-2 -ml-2 rounded-full bg-white/10 text-white backdrop-blur-sm active:scale-90 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-xl font-bold text-white tracking-tight">Notifikasi</h1>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto no-scrollbar bg-gradient-to-b from-purple-700 via-gray-50 to-white p-5 space-y-4">
        @forelse($notifications as $notif)
            <div class="p-4 rounded-2xl {{ $notif->read_at ? 'bg-white border-gray-100' : 'bg-purple-50 border-purple-100' }} border shadow-sm">
                <div class="flex gap-3">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-purple-600 flex-none border">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ $notif->data['title'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $notif->data['body'] ?? '' }}</p>
                        <p class="text-[10px] text-gray-400 mt-2">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20 opacity-40">
                <p class="text-sm font-bold">Belum ada notifikasi</p>
            </div>
        @endforelse
    </div>
</div>