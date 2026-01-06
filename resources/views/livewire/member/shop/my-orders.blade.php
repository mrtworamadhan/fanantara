<div class="h-screen bg-gray-50 flex flex-col relative overflow-hidden font-sans">

    <div class="bg-white shadow-sm sticky top-0 z-30 flex-none">
        <div class="px-5 py-4 flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="p-2 -ml-2 text-gray-600 hover:bg-gray-100 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">Pesanan Saya</h1>
        </div>

        <div class="flex px-5  gap-4 pb-0">
            @php
                $tabs = [
                    'all' => 'Semua',
                    'pending' => 'Belum Bayar',
                    'process' => 'Diproses',
                    'done' => 'Selesai'
                ];
            @endphp

            @foreach($tabs as $key => $label)
                <button 
                    wire:click="setFilter('{{ $key }}')"
                    class="pb-3 text-sm font-bold whitespace-nowrap border-b-2 transition-colors
                    {{ $statusFilter === $key ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="flex-1 overflow-y-auto no-scrollbar bg-gray-100 px-4 pt-4 pb-24">
        
        @forelse($orders as $order)
            <div wire:click="showDetail({{ $order->id }})" class="bg-white p-4 rounded-2xl mb-2 shadow-sm border border-gray-100 active:scale-[0.98] transition-transform cursor-pointer">
                
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide
                            {{ match($order->status) {
                                'completed' => 'bg-emerald-100 text-emerald-700',
                                'processing' => 'bg-amber-100 text-amber-700',
                                'pending' => 'bg-gray-100 text-gray-600',
                                'cancelled' => 'bg-red-100 text-red-600',
                                default => 'bg-gray-100 text-gray-600'
                            } }}">
                            {{ match($order->status) {
                                'completed' => 'Selesai',
                                'processing' => 'Diproses',
                                'pending' => 'Menunggu Bayar',
                                'cancelled' => 'Dibatalkan',
                                default => $order->status
                            } }}
                        </span>
                        <p class="text-[10px] text-gray-400 mt-1.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <p class="text-xs font-mono text-gray-400">#{{ $order->order_number }}</p>
                </div>

                <div class="flex gap-3 items-center border-t border-gray-50 pt-3">
                    @if($order->items->first())
                        <img src="{{ asset('storage/' . $order->items->first()->product->image) }}" class="w-12 h-12 rounded-lg bg-gray-100 object-cover flex-none">
                    @endif

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-800 line-clamp-1">
                            {{ $order->items->first()->product->name ?? 'Produk dihapus' }}
                        </p>
                        @if($order->items->count() > 1)
                            <p class="text-[10px] text-gray-500">+ {{ $order->items->count() - 1 }} produk lainnya</p>
                        @else
                            <p class="text-[10px] text-gray-500">{{ $order->items->first()->quantity }} pcs</p>
                        @endif
                    </div>

                    <div class="text-right">
                        <p class="text-xs text-gray-400 mb-0.5">Total Belanja</p>
                        <p class="text-sm font-black text-emerald-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-20 opacity-50 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <p class="text-sm font-bold text-gray-800">Belum ada pesanan</p>
                @if($statusFilter !== 'all')
                    <p class="text-xs text-gray-500 mt-1">Coba ganti filter status lainnya.</p>
                @else
                    <a href="{{ route('member.marketplace') }}" class="mt-3 text-xs font-bold text-emerald-600 hover:underline">Mulai Belanja</a>
                @endif
            </div>
        @endforelse

        
    </div>

    @if($isShowDetail && $selectedOrder)
        <div class="fixed inset-0 z-50 flex items-end justify-center sm:items-center">
            
            <div wire:click="closeDetail" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

            <div class="bg-white w-full max-w-lg rounded-t-[2rem] sm:rounded-2xl relative z-10 max-h-[90vh] flex flex-col shadow-2xl animate-slide-up">
                
                <div class="w-full flex justify-center pt-3 pb-1 sm:hidden">
                    <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
                </div>

                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Detail Pesanan</h2>
                        <p class="text-xs text-gray-500">#{{ $selectedOrder->order_number }}</p>
                    </div>
                    <button wire:click="closeDetail" class="p-2 bg-gray-100 rounded-full hover:bg-gray-200">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar">
                    
                    <div class="bg-emerald-50 rounded-xl p-4 flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center flex-none">
                            @if($selectedOrder->status == 'completed')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @elseif($selectedOrder->status == 'processing')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">
                                {{ match($selectedOrder->status) {
                                    'completed' => 'Pesanan Selesai',
                                    'processing' => 'Sedang Diproses / Dikemas',
                                    'pending' => 'Menunggu Pembayaran',
                                    'cancelled' => 'Pesanan Dibatalkan',
                                    default => 'Status Tidak Diketahui'
                                } }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ match($selectedOrder->status) {
                                    'completed' => 'Terima kasih telah berbelanja.',
                                    'processing' => 'Barang sedang disiapkan oleh petugas.',
                                    'pending' => 'Segera selesaikan pembayaran Anda.',
                                    default => ''
                                } }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Produk Dibeli</h3>
                        <div class="space-y-3">
                            @foreach($selectedOrder->items as $item)
                                <div class="flex gap-3">
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="w-14 h-14 rounded-lg bg-gray-100 object-cover flex-none">
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-gray-800 line-clamp-2">{{ $item->product->name }}</p>
                                        <div class="flex justify-between mt-1">
                                            <p class="text-xs text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                            <p class="text-xs font-bold text-gray-800">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Info Pengiriman</h3>
                        <p class="text-sm text-gray-800">{{ $selectedOrder->member->user->name }}</p>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                            {{ Str::after($selectedOrder->notes, '|') ?: 'Alamat sesuai data member' }}
                        </p>
                    </div>

                </div>

                <div class="p-5 border-t border-gray-100 bg-gray-50 rounded-b-3xl">
                    <div class="flex justify-between items-center mb-0">
                        <span class="text-xs font-bold text-gray-600">Total Pembayaran</span>
                        <span class="text-lg font-black text-emerald-600">Rp {{ number_format($selectedOrder->total_amount, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($selectedOrder->status == 'pending')
                        @php
                            $adminPhone = '6285158611302';
                            $message = "Halo Admin, saya ingin konfirmasi pembayaran.\n\n" .
                                       "No. Order: *{$selectedOrder->order_number}*\n" .
                                       "Nama: {$selectedOrder->member->user->name}\n" .
                                       "Total: Rp " . number_format($selectedOrder->total_amount, 0, ',', '.') . "\n\n" .
                                       "Berikut bukti transfer saya (lampirkan foto):";
                            
                            $waUrl = "https://wa.me/{$adminPhone}?text=" . urlencode($message);
                        @endphp

                        <a href="{{ $waUrl }}" 
                           target="_blank" 
                           class="w-full mt-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-500/20 active:scale-95 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            Konfirmasi via WhatsApp
                        </a>
                    @endif
                    
                    @if($selectedOrder->status == 'processing')
                        <div class="w-full mt-4 py-3 bg-amber-100 text-amber-700 text-xs font-bold rounded-xl flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Sedang diproses admin
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        .animate-slide-up { animation: slideUp 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) forwards; transform: translateY(100%); }
        @keyframes slideUp { to { transform: translateY(0); } }
    </style>
</div>