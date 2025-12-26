<div class="min-h-screen bg-gray-50 flex flex-col relative pb-10">
    
    <div class="h-48 {{ $is_rejected ? 'bg-gradient-to-br from-red-600 to-orange-700' : 'bg-gradient-to-br from-emerald-600 to-teal-800' }} rounded-b-[2.5rem] relative shadow-lg transition-colors duration-500">
        <div class="absolute inset-0 bg-white/10 opacity-30 pattern-dots"></div>
        
        <div class="pt-8 px-6 text-center text-white">
            <h1 class="text-2xl font-bold tracking-tight">
                {{ $is_rejected ? 'Perbaikan Data' : 'Aktivasi Anggota' }}
            </h1>
            <p class="text-white/90 text-sm mt-1">
                {{ $is_rejected ? 'Mohon perbaiki bukti pembayaran Anda.' : 'Selesaikan pembayaran untuk mengaktifkan akun.' }}
            </p>
        </div>
    </div>

    <div class="px-6 -mt-20 z-10">
        
        @if($is_rejected)
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-4 shadow-sm animate-pulse">
                <div class="flex items-start gap-3">
                    <div class="bg-red-100 p-2 rounded-full shrink-0 text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-red-800 font-bold text-sm">Pendaftaran Ditolak</h4>
                        <p class="text-red-600 text-xs mt-1 leading-relaxed">
                            Alasan: <span class="font-semibold italic">"{{ $rejection_note }}"</span>
                        </p>
                        <p class="text-red-500 text-[10px] mt-2">Silakan upload ulang bukti yang valid di bawah ini.</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-xl p-6 mb-6 border border-gray-100">
            
            @if(!$is_submitted || $is_rejected)
                <div class="text-center mb-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Total Tagihan</p>
                    <div class="flex items-center justify-center gap-1 text-emerald-600">
                        <span class="text-2xl font-bold mt-1">Rp</span>
                        <span class="text-5xl font-black tracking-tight">{{ number_format($total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-2 inline-flex items-center gap-2 bg-yellow-50 text-yellow-700 px-3 py-1 rounded-lg border border-yellow-100">
                        <span class="text-xs font-medium">Kode Unik: </span>
                        <span class="text-sm font-bold">{{ str_pad($unique_code, 3, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 relative group hover:border-emerald-200 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-lg shadow-sm flex items-center justify-center border border-gray-100 text-blue-700 font-black italic text-lg">BCA</div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-medium">Bank Central Asia</p>
                                <p class="text-sm font-bold text-gray-800">{{ $bank_name }}</p>
                                <p class="text-lg font-mono font-bold text-gray-900 tracking-wider mt-0.5">{{ $bank_account }}</p>
                            </div>
                        </div>
                        <button onclick="navigator.clipboard.writeText('{{ $bank_account }}'); alert('Nomor rekening disalin!')" class="absolute top-4 right-4 p-2 rounded-lg bg-white shadow-sm border border-gray-100 text-emerald-600 hover:bg-emerald-50 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="mt-8">
                    <form wire:submit.prevent="submitPayment" class="space-y-4">
                        <div>
                            <label class="text-sm font-bold text-gray-800 mb-2 block">
                                {{ $is_rejected ? 'Upload Bukti Baru' : 'Upload Bukti Transfer' }}
                            </label>
                            
                            <label for="proof" class="block w-full cursor-pointer group">
                                <div class="w-full h-40 border-2 border-dashed {{ $is_rejected ? 'border-red-300 bg-red-50' : 'border-gray-300 bg-gray-50' }} rounded-2xl flex flex-col items-center justify-center group-hover:bg-white transition-all relative overflow-hidden">
                                    
                                    @if ($payment_proof)
                                        <img src="{{ $payment_proof->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover p-2 rounded-2xl">
                                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span class="text-white text-xs font-bold bg-black/50 px-3 py-1 rounded-full">Ganti Gambar</span>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center mb-2 {{ $is_rejected ? 'text-red-500' : 'text-emerald-500' }}">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        </div>
                                        <p class="text-sm text-gray-500 font-medium">Klik untuk upload bukti</p>
                                    @endif
                                    
                                    <input id="proof" type="file" wire:model="payment_proof" class="hidden" accept="image/*">
                                </div>
                            </label>
                            @error('payment_proof') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" wire:loading.attr="disabled" 
                            class="w-full py-4 rounded-xl font-bold text-white {{ $is_rejected ? 'bg-red-600 hover:bg-red-700 shadow-red-500/30' : 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/30' }} shadow-lg transition-all flex justify-center items-center">
                            <span wire:loading.remove>{{ $is_rejected ? 'Kirim Ulang Bukti' : 'Kirim Bukti Pembayaran' }}</span>
                            <span wire:loading class="flex items-center gap-2">Mengirim...</span>
                        </button>
                    </form>
                </div>

            @else
                <div class="text-center py-8">
                    <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
                        <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Bukti Diterima!</h3>
                    
                    @if(isset(auth()->user()->member->activation_payment_data['proof_path']))
                        <div class="mx-auto w-40 h-40 mt-4 mb-4 border-4 border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                            <img src="{{ Storage::url(auth()->user()->member->activation_payment_data['proof_path']) }}" 
                                 class="w-full h-full object-cover">
                        </div>
                    @endif

                    <p class="text-gray-500 text-sm leading-relaxed px-4">
                        Terima kasih. Admin kami sedang memverifikasi pembayaran Anda.<br>
                        Proses ini biasanya memakan waktu <strong>1x24 Jam Kerja</strong>.
                    </p>
                    
                    <div class="mt-6 p-3 bg-yellow-50 rounded-xl border border-yellow-100 inline-flex items-center gap-2">
                        <span class="w-2 h-2 bg-yellow-400 rounded-full animate-ping"></span>
                        <span class="text-xs font-bold text-yellow-700">Status: Menunggu Approval</span>
                    </div>
                </div>
            @endif

            <div class="mt-8 pt-6 border-t border-dashed border-gray-200 text-center">
                <p class="text-xs text-gray-500 mb-3">
                    {{ $is_rejected ? 'Butuh bantuan soal penolakan ini?' : 'Kesulitan melakukan pembayaran?' }}
                </p>
                
                <a href="https://wa.me/6285158611302?text=Halo%20CS%20Fanantara,%20saya%20{{ urlencode(auth()->user()->name) }}%20(ID:%20{{ auth()->id() }}).%20{{ $is_rejected ? 'Saya%20ingin%20tanya%20soal%20penolakan%20pendaftaran.' : 'Saya%20ingin%20konfirmasi%20pembayaran.' }}" 
                   target="_blank"
                   class="inline-flex items-center justify-center gap-2 w-full py-3 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 font-semibold text-sm hover:bg-emerald-100 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487 2.982 1.288 2.982.859 3.526.801.544-.058 1.758-.718 2.006-1.411.248-.693.248-1.288.173-1.411z"/></svg>
                    Hubungi CS (WhatsApp)
                </a>
            </div>

        </div>

        <div class="text-center">
            <a href="{{ route('logout') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-red-500 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Keluar / Ganti Akun
            </a>
        </div>

    </div>
</div>