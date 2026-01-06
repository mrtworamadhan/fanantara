<div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-6 relative overflow-hidden">
    
    <div class="absolute top-0 left-0 w-full h-85 bg-emerald-600 rounded-b-[3rem] z-0"></div>

    <div class="relative z-10 w-full max-w-md animate-fade-in-up">
        
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden text-center p-8 relative">
            
            {{-- Icon Ceklis --}}
            <div class="flex justify-center mb-5">
                <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center">
                    <div class="w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center animate-scale-in">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                </div>
            </div>

            <h1 class="text-2xl font-black text-gray-800 mb-2">Pembayaran Berhasil!</h1>
            <p class="text-sm text-gray-500 mb-8">Terima kasih telah berbelanja di Koperasi.</p>

            <div class="bg-gray-50 rounded-2xl p-5 mb-6 text-left border border-gray-100 border-dashed">
                <div class="flex justify-between mb-3 border-b border-gray-200 border-dashed pb-3">
                    <span class="text-xs text-gray-400 font-bold uppercase">No. Order</span>
                    <span class="text-sm font-bold text-gray-800 font-mono">{{ $order->order_number }}</span>
                </div>
                
                <div class="flex justify-between mb-2">
                    <span class="text-xs text-gray-500">Waktu</span>
                    <span class="text-xs font-bold text-gray-700">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
                
                <div class="flex justify-between mb-2">
                    <span class="text-xs text-gray-500">Metode Bayar</span>
                    <span class="text-xs font-bold text-emerald-600">
                        {{ $order->payment_status == 'paid' ? 'Simpanan Sukarela' : 'Transfer Bank' }}
                    </span>
                </div>

                <div class="flex justify-between mt-4 pt-3 border-t border-gray-200 border-dashed">
                    <span class="text-sm font-bold text-gray-600">Total Bayar</span>
                    <span class="text-lg font-black text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="space-y-3 mb-8">
                @foreach($order->items->take(3) as $item)
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('storage/' . $item->product->image) }}" class="w-10 h-10 rounded-lg bg-gray-100 object-cover">
                        <div class="text-left flex-1 min-w-0">
                            <p class="text-xs font-bold text-gray-800 line-clamp-1">{{ $item->product->name }}</p>
                            <p class="text-[10px] text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach

                @if($order->items->count() > 3)
                    <p class="text-xs text-gray-400 italic text-center pt-2">+ {{ $order->items->count() - 3 }} produk lainnya</p>
                @endif
            </div>

            <div class="space-y-3">
                <a href="{{ route('member.marketplace') }}" class="block w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-transform active:scale-95">
                    Belanja Lagi
                </a>
                
                <a href="{{ route('dashboard') }}" class="block w-full py-3.5 bg-white border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors">
                    Kembali ke Dashboard
                </a>
            </div>

        </div>
        
        <p class="text-center text-emerald-800/50 text-[10px] mt-6">
            Bukti transaksi ini telah dikirim ke email Anda.
        </p>
    </div>

    <style>
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        
        .animate-scale-in { animation: scaleIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.3s forwards; opacity: 0; transform: scale(0); }
        @keyframes scaleIn { to { opacity: 1; transform: scale(1); } }
    </style>
</div>