<div class="h-screen bg-gray-50 flex flex-col relative overflow-hidden font-sans">
    
    <div class="h-48 rounded-b-[2.5rem] relative shadow-lg z-0 flex-none overflow-hidden" 
         style="background: {{ $is_rejected ? 'linear-gradient(135deg, #dc2626 0%, #991b1b 100%)' : 'linear-gradient(135deg, #059669 0%, #115e59 100%)' }};">
        
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0 0 L100 100 L0 100 Z" fill="white"></path></svg>
        </div>
        
        <div class="pt-10 px-6 text-center text-white relative z-10">
            <h1 class="text-2xl font-black tracking-tight drop-shadow-sm uppercase">
                {{ $is_rejected ? 'Perbaikan Data' : 'Aktivasi Akun' }}
            </h1>
            <p class="text-emerald-50/80 text-[10px] font-bold uppercase tracking-[2px] mt-1">
                {{ $is_rejected ? 'Upload ulang bukti bayar Anda' : 'Selesaikan administrasi keanggotaan' }}
            </p>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto no-scrollbar -mt-16 px-6 pb-10 relative z-10">
        
        <div class="bg-white rounded-3xl shadow-xl shadow-emerald-900/5 border border-gray-100 overflow-hidden mb-6 animate-fade-in">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Total Tagihan</p>
                        <p class="text-3xl font-black text-emerald-700 leading-none mt-2">
                            Rp {{ number_format($total_amount, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-amber-100 text-amber-700 px-3 py-1.5 rounded-xl border border-amber-200 text-center">
                        <p class="text-[8px] font-bold uppercase leading-none mb-1">Kode Unik</p>
                        <p class="text-sm font-black leading-none">{{ str_pad($unique_code, 3, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>

                <div class="space-y-3 pt-4 border-t border-gray-50">
                    @foreach($fees as $fee)
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500 font-medium">{{ $fee['name'] ?? $fee->name }}</span>
                            <span class="text-gray-800 font-bold">Rp {{ number_format($fee['amount'] ?? $fee->amount, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <div class="flex justify-between text-xs text-amber-600 font-bold italic">
                        <span>Kode Verifikasi</span>
                        <span>+Rp {{ $unique_code }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($is_rejected)
            <div class="bg-red-50 border border-red-100 rounded-2xl p-4 mb-6 animate-pulse">
                <div class="flex items-start gap-3 text-red-700">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <p class="text-xs font-black uppercase tracking-tight">Ditolak Admin</p>
                        <p class="text-[11px] mt-1 italic">"{{ $rejection_note }}"</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-xl shadow-emerald-900/5 border border-gray-100 p-6 mb-6">
            @if(!$is_submitted || $is_rejected)
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Metode Transfer</h4>
                <div class="space-y-3">
                    @forelse($banks as $bank)
                        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 relative group active:scale-[0.98] transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm p-2">
                                    @if($bank->logo)
                                        <img src="{{ asset('storage/'.$bank->logo) }}" class="max-h-full object-contain">
                                    @else
                                        <span class="text-[10px] font-black text-blue-800">{{ $bank->bank_name }}</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase leading-none mb-1">{{ $bank->bank_name }}</p>
                                    <p class="text-base font-black text-gray-900 tracking-wider font-mono">{{ $bank->account_number }}</p>
                                    <p class="text-[10px] text-gray-500 font-medium">a.n {{ $bank->account_holder }}</p>
                                </div>
                                <button onclick="navigator.clipboard.writeText('{{ $bank->account_number }}'); alert('Nomor rekening disalin!')" 
                                        class="p-2 text-emerald-600 bg-white shadow-sm border border-emerald-50 rounded-xl active:bg-emerald-50 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 text-center italic">Hubungi admin untuk data rekening.</p>
                    @endforelse
                </div>

                <div class="mt-8">
                    <form wire:submit.prevent="submitPayment" class="space-y-4">
                        <label for="proof" class="block w-full cursor-pointer group">
                            <div class="w-full h-44 border-2 border-dashed {{ $is_rejected ? 'border-red-200 bg-red-50/30' : 'border-emerald-200 bg-emerald-50/30' }} rounded-3xl flex flex-col items-center justify-center group-hover:bg-white transition-all relative overflow-hidden">
                                @if ($payment_proof)
                                    <img src="{{ $payment_proof->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover p-2 rounded-[2rem]">
                                    <div class="absolute inset-0 bg-emerald-900/40 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="text-white text-xs font-black uppercase bg-white/20 px-4 py-2 rounded-xl border border-white/30">Ganti Bukti</span>
                                    </div>
                                @else
                                    <div class="w-14 h-14 bg-white rounded-full shadow-md flex items-center justify-center mb-3 {{ $is_rejected ? 'text-red-500' : 'text-emerald-600' }}">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    </div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tap untuk Upload Bukti</p>
                                @endif
                                <input id="proof" type="file" wire:model="payment_proof" class="hidden" accept="image/*">
                            </div>
                        </label>
                        @error('payment_proof') <span class="text-red-500 text-[10px] font-bold uppercase ml-2">{{ $message }}</span> @enderror

                        <button type="submit" wire:loading.attr="disabled" 
                            class="w-full py-4 rounded-2xl font-black text-white shadow-lg shadow-emerald-900/20 transition-all flex justify-center items-center gap-2 transform active:scale-95 uppercase tracking-widest text-sm"
                            style="background: {{ $is_rejected ? '#dc2626' : '#059669' }};">
                            <span wire:loading.remove>{{ $is_rejected ? 'Kirim Ulang Bukti' : 'Aktivasi Sekarang' }}</span>
                            <span wire:loading class="flex items-center gap-2">Memproses...</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="text-center py-6">
                    <div class="w-24 h-24 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-6 shadow-inner relative">
                        <div class="absolute inset-0 rounded-full border-4 border-emerald-100 animate-ping opacity-25"></div>
                        <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-2 uppercase tracking-tight">Dalam Antrean</h3>
                    <p class="text-gray-500 text-xs leading-relaxed px-4 font-medium">
                        Terima kasih! Bukti transfer Anda sudah kami terima. Admin Fanantara akan segera memverifikasi data Anda dalam waktu <strong>1x24 jam</strong>.
                    </p>
                    
                    <div class="mt-8 pt-8 border-t border-gray-50">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Bukti yang Dikirim</p>
                        <div class="mx-auto w-48 h-48 border-8 border-gray-50 rounded-3xl overflow-hidden shadow-sm bg-gray-100">
                             <img src="{{ asset('storage/' . auth()->user()->member->activation_payment_data['proof_path']) }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-3">
            <a href="https://wa.me/6285158611302?text=Halo%20Admin%20Fanantara,%20bisa%20bantu%20verifikasi%20pembayaran%20saya?" 
               target="_blank"
               class="flex items-center justify-center gap-3 w-full py-4 rounded-2xl bg-white border border-gray-200 text-emerald-700 font-bold text-sm shadow-sm active:bg-gray-50 transition-all">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487 2.982 1.288 2.982.859 3.526.801.544-.058 1.758-.718 2.006-1.411.248-.693.248-1.288.173-1.411z"/></svg>
                Chat Admin Bantuan
            </a>

            <div class="text-center py-4">
                <a href="{{ route('logout') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-red-500 uppercase tracking-widest transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout / Ganti Akun
                </a>
            </div>
        </div>

    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</div>