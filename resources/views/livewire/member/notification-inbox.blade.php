<div class="min-h-screen bg-gray-50 flex flex-col font-sans">
    <div class="bg-white px-5 py-4 shadow-sm sticky top-0 z-10 flex items-center gap-3">
        <a href="{{ route('dashboard') }}" class="text-gray-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg></a>
        <h1 class="text-lg font-bold">Notifikasi</h1>
    </div>

    <div class="p-5 space-y-4">
        @forelse($notifications as $notif)
            <div class="p-4 rounded-2xl {{ $notif->read_at ? 'bg-white border-gray-100' : 'bg-emerald-50 border-emerald-100' }} border shadow-sm">
                <div class="flex gap-3">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-emerald-600 flex-none border">
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