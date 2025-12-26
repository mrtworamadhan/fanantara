<div class="h-screen bg-gray-50 flex flex-col relative overflow-hidden font-sans">
    
    <div class="bg-emerald-700 px-5 pt-5 pb-4 shadow-lg z-40 flex-none">
        <div class="flex items-center gap-3 mb-5">
            <a href="{{ route('dashboard') }}" class="p-2 rounded-full bg-white/10 text-white backdrop-blur-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-xl font-bold text-white tracking-tight">Riwayat Mutasi</h1>
        </div>

        <div class="flex p-1 bg-emerald-800/40 backdrop-blur-md rounded-xl border border-emerald-500/30">
            <button wire:click="$set('filter', 'all')" class="flex-1 py-2 text-xs font-bold rounded-lg transition-all {{ $filter == 'all' ? 'bg-white text-emerald-700 shadow-md' : 'text-emerald-100' }}">Semua</button>
            <button wire:click="$set('filter', 'deposit')" class="flex-1 py-2 text-xs font-bold rounded-lg transition-all {{ $filter == 'deposit' ? 'bg-white text-emerald-700 shadow-md' : 'text-emerald-100' }}">Masuk</button>
            <button wire:click="$set('filter', 'withdrawal')" class="flex-1 py-2 text-xs font-bold rounded-lg transition-all {{ $filter == 'withdrawal' ? 'bg-white text-emerald-700 shadow-md' : 'text-emerald-100' }}">Keluar</button>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto no-scrollbar bg-gradient-to-b from-emerald-700 via-gray-50 to-white px-5 pt-4 pb-24">
        
        @forelse($groupedTransactions as $date => $items)
            <div class="mb-6 animate-fade-in">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-[10px] font-black text-white bg-emerald-500/80 px-2 py-0.5 rounded-full uppercase tracking-tighter shadow-sm">
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}
                    </span>
                    <div class="h-px bg-emerald-200 flex-1"></div>
                </div>

                <div class="space-y-3">
                    @foreach($items as $trx)
                        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between transition-all active:scale-95">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $trx->type == 'deposit' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                                    @if($trx->type == 'deposit')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                    @endif
                                </div>

                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $trx->account->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium truncate w-40">{{ $trx->notes ?? 'Transaksi Koperasi' }}</p>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-sm font-black {{ $trx->type == 'deposit' ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ $trx->type == 'deposit' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                </p>
                                <p class="text-[9px] text-gray-400">{{ $trx->created_at->format('H:i') }} WIB</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center mt-20 opacity-40">
                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <p class="text-xs font-bold mt-2">Belum ada transaksi</p>
            </div>
        @endforelse

    </div>

    <x-mobile.bottom-nav active="history" />

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</div>