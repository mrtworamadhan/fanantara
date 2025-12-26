<div class="h-screen w-full bg-gray-50 flex flex-col relative overflow-hidden"
     x-data="{ 
        tab: 'rekening',       
        showBalance: false
     }">

    <div class="absolute top-0 left-0 right-0 transition-all duration-700 ease-in-out z-0"
         :class="{ 
            'h-[220px] rounded-b-[2.5rem] bg-gradient-to-br from-emerald-700 to-teal-800': tab !== 'portofolio', 
            'h-full rounded-none bg-gradient-to-b from-teal-900 via-teal-900 to-gray-900': tab === 'portofolio' 
         }">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-16 -mt-16 blur-3xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-white/10 rounded-full -ml-10 -mb-10 blur-2xl opacity-30"></div>
    </div>

    <div class="relative z-30 flex-none shadow-sm shadow-black/5">
        
        <div class="px-6 pt-10 pb-4 flex justify-between items-center">
            <a href="{{ route('member.profile') }}" >
                <div class="flex items-center gap-3">
                
                    <div class="w-10 h-10 rounded-full border-2 border-white/30 overflow-hidden shadow-sm backdrop-blur-sm">
                        @if(!empty($member_photo))
                            <img 
                                src="{{ asset('storage/' . $member_photo) }}"
                                class="w-full h-full object-cover group-hover:opacity-80 transition-opacity"
                                alt="Avatar"
                            >
                        @else
                            <img 
                                src="https://ui-avatars.com/api/?name={{ urlencode($member_name ?? 'User') }}&background=059669&color=fff&size=128"
                                class="w-full h-full object-cover group-hover:opacity-80 transition-opacity"
                                alt="Avatar"
                            >
                        @endif
                    </div>

                    <div class="text-white">
                        <h1 class="text-sm font-bold leading-tight drop-shadow-md">{{ $member_name }}</h1>
                        <div class="flex items-center gap-1 opacity-90">
                            <span class="text-[10px] bg-white/20 px-1.5 py-0.5 rounded backdrop-blur-md border border-white/10">{{ $member_number }}</span>
                        </div>
                    </div>
                </div>
            </a>
            
            <button class="relative p-2 rounded-full bg-white/10 hover:bg-white/20 transition-colors text-white backdrop-blur-md border border-white/10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <span class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
            </button>
        </div>

        <div class="px-6 pb-4">
            <div class="bg-black/20 backdrop-blur-lg p-1 rounded-2xl flex relative border border-white/10">
                <div class="absolute top-1 bottom-1 bg-white rounded-xl shadow-lg transition-all duration-500 cubic-bezier(0.4, 0, 0.2, 1)"
                     style="width: calc(33.33% - 5px);"
                     :class="{ 
                        'translate-x-0 left-1': tab === 'rekening', 
                        'translate-x-[100%] left-[2.5px]': tab === 'shu', 
                        'translate-x-[200%] left-[4px]': tab === 'portofolio' 
                     }">
                </div>

                <button @click="tab = 'rekening'" class="flex-1 py-2.5 text-xs font-bold text-center relative z-10 transition-colors duration-300"
                    :class="tab === 'rekening' ? 'text-emerald-700' : 'text-white/80 hover:text-white'">
                    Rekening
                </button>
                <button @click="tab = 'shu'" class="flex-1 py-2.5 text-xs font-bold text-center relative z-10 transition-colors duration-300"
                    :class="tab === 'shu' ? 'text-emerald-700' : 'text-white/80 hover:text-white'">
                    SHU
                </button>
                <button @click="tab = 'portofolio'" class="flex-1 py-2.5 text-xs font-bold text-center relative z-10 transition-colors duration-300"
                    :class="tab === 'portofolio' ? 'text-teal-900' : 'text-white/80 hover:text-white'">
                    Portofolio
                </button>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto overflow-x-hidden relative z-10 pb-32 px-6 pt-4 custom-scrollbar">
        
        <div class="relative min-h-[400px]">

            <div x-show="tab !== 'portofolio'" 
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-[-20px]"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="mb-6">
                
                <div class="bg-white rounded-3xl shadow-xl shadow-emerald-900/10 p-6 border border-white relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <svg class="w-24 h-24 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 9l2.5-1.25L12 8.5l-2.5 1.25L12 11zm0 2.5l-5-2.5-5 2.5L12 22l10-8.5-5-2.5-5 2.5z"/></svg>
                    </div>

                    <div class="mb-6 relative z-10">
                        <div class="flex justify-between items-center mb-1">
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Total Aset</p>
                            <button @click="showBalance = !showBalance" class="text-gray-400 hover:text-emerald-600 transition-colors p-1 -mr-2">
                                <svg x-show="!showBalance" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="showBalance" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                        <div class="h-10 flex items-center">
                            <template x-if="showBalance">
                                <h2 class="text-3xl font-black text-gray-800 tracking-tight"><span class="text-lg font-bold text-gray-400 mr-1">Rp</span>{{ number_format($total_asset, 0, ',', '.') }}</h2>
                            </template>
                            <template x-if="!showBalance">
                                <div class="flex gap-1.5 mt-2"><div class="w-3 h-3 bg-gray-200 rounded-full animate-pulse"></div><div class="w-3 h-3 bg-gray-200 rounded-full animate-pulse delay-75"></div><div class="w-3 h-3 bg-gray-200 rounded-full animate-pulse delay-100"></div><div class="w-3 h-3 bg-gray-200 rounded-full animate-pulse delay-150"></div></div>
                            </template>
                        </div>
                    </div>
                    <div class="h-px bg-gray-100 w-full mb-4 group-hover:bg-emerald-100 transition-colors"></div>
                    <div class="flex justify-between items-center relative z-10">
                        <div>
                            <p class="text-[10px] text-gray-400 font-semibold mb-0.5">Saldo Cair (Sukarela)</p>
                            <template x-if="showBalance"><p class="text-emerald-600 font-bold text-lg">Rp {{ number_format($saldo_sukarela, 0, ',', '.') }}</p></template>
                            <template x-if="!showBalance"><div class="flex gap-1 h-6 items-center"><div class="w-2 h-2 bg-emerald-100 rounded-full"></div><div class="w-2 h-2 bg-emerald-100 rounded-full"></div><div class="w-2 h-2 bg-emerald-100 rounded-full"></div></div></template>
                        </div>
                        <div class="flex gap-2">
                            <button class="bg-emerald-50 text-emerald-600 p-2.5 rounded-xl hover:bg-emerald-100 transition-colors shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></button>
                            <button class="bg-orange-50 text-orange-600 p-2.5 rounded-xl hover:bg-orange-100 transition-colors shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg></button>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="tab === 'rekening'" 
                 x-transition:enter="transition ease-out duration-300 delay-100"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Rincian Simpanan</h3>
                <div class="space-y-3">
                    @foreach($accounts_list as $account)
                        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center transition-transform active:scale-95">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center border border-gray-50 shadow-sm
                                    {{ $account['code'] == 'SP' ? 'bg-blue-50 text-blue-600' : ($account['code'] == 'SW' ? 'bg-purple-50 text-purple-600' : 'bg-emerald-50 text-emerald-600') }}">
                                    <span class="font-bold text-[10px]">{{ $account['code'] }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $account['name'] }}</p>
                                    <p class="text-[10px] text-gray-400">Akun Aktif</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <template x-if="showBalance"><p class="text-sm font-bold text-gray-800">Rp {{ number_format($account['balance'], 0, ',', '.') }}</p></template>
                                <template x-if="!showBalance"><p class="text-sm font-bold text-gray-300">••••••</p></template>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div x-show="tab === 'shu'" style="display: none;"
                x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 translate-x-4"
                x-transition:enter-end="opacity-100 translate-x-0">
                
                <div class="bg-gradient-to-r from-orange-400 to-pink-500 rounded-3xl p-6 text-white shadow-xl shadow-orange-500/20 mb-6 relative overflow-hidden mt-2">
                    <div class="absolute right-0 bottom-0 opacity-20 -mr-6 -mb-6">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39h-2.01c-.06-1.01-.71-1.76-2.13-1.76-1.63 0-2.22.88-2.22 1.51 0 .7.47 1.39 2.4 1.87 2.71.69 4.43 1.7 4.43 3.94 0 1.99-1.56 3.23-3.32 3.59z"/></svg>
                    </div>
                    <p class="text-xs font-medium text-orange-100 mb-1">Estimasi SHU Tahun Ini</p>
                    
                    <h2 class="text-3xl font-black mb-4">
                        Rp {{ number_format($shu_data['total_estimation'] ?? 0, 0, ',', '.') }}
                        <span class="text-sm font-normal opacity-80">*</span>
                    </h2>
                    
                    <div class="grid grid-cols-2 gap-4 text-xs border-t border-white/20 pt-4">
                        <div>
                            <p class="opacity-80">Jasa Modal</p>
                            <p class="font-bold text-lg">
                                Rp {{ number_format($shu_data['breakdown']['jasa_modal'] ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <p class="opacity-80">Jasa Kontribusi</p>
                            <p class="font-bold text-lg">
                                Rp {{ number_format($shu_data['breakdown']['jasa_usaha'] ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Persentase Jasa Modal Member (Opsional, kalau mau nampilin porsi dia) --}}
                    {{-- <span class="text-xs font-bold text-white opacity-50 mt-2 block">
                        Berdasarkan Persentase Modal & Belanja Anda
                    </span> --}}
                </div>
                <p class="text-[10px] text-white/50 mt-4 text-center">
                    Pembaruan terakhir: {{ \Carbon\Carbon::parse($shu_data['last_update'] ?? now())->diffForHumans() }}
                </p>

                <div class="bg-white rounded-2xl border border-gray-100 p-5 space-y-5 shadow-sm">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Ketentuan Pembagian (RAT)</h4>
                    
                    @foreach($allocations as $alloc)
                        @php
                            $color = match($alloc->code) {
                                'JM' => 'emerald',
                                'JU' => 'blue',
                                default => 'gray',
                            };
                        @endphp

                        <div class="space-y-1">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-600">{{ $alloc->name }}</span>
                                <span class="text-xs font-bold text-{{ $color }}-600">{{ $alloc->percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 h-1.5 rounded-full">
                                <div class="bg-{{ $color }}-500 h-1.5 rounded-full transition-all duration-1000" 
                                    style="width: {{ $alloc->percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4 text-[10px] text-gray-400 text-center italic">
                    *Angka ini adalah estimasi berdasarkan Laba Berjalan Koperasi saat ini. Nilai final ditentukan saat RAT.
                </div>
            </div>


            <div x-show="tab === 'portofolio'" style="display: none;" class="pt-2"
                 x-transition:enter="transition ease-out duration-700 delay-100"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <div class="text-center mb-8">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-emerald-400 to-teal-600 rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/50 mb-4 animate-pulse">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white tracking-tight">Ringkasan Finansial</h2>
                    <p class="text-teal-200 text-sm mt-1 opacity-80">Analisa performa akun Anda</p>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-white/10 text-white mb-4 shadow-lg">
                    <p class="text-xs text-teal-200 uppercase tracking-widest mb-1">Total Aset Bersih</p>
                    <h3 class="text-3xl font-bold mb-4">Rp {{ number_format($total_asset, 0, ',', '.') }}</h3>
                    <div class="h-1.5 w-full bg-black/20 rounded-full mb-3 overflow-hidden">
                        <div class="h-full bg-teal-400 rounded-full shadow-[0_0_10px_rgba(45,212,191,0.5)]" style="width: 70%"></div>
                    </div>
                    <p class="text-xs text-teal-100">Pertumbuhan aset <span class="font-bold text-white">+5%</span> bulan ini.</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-5 border border-white/5 shadow-lg hover:bg-white/10 transition-colors">
                        <p class="text-[10px] text-teal-200 uppercase mb-2 font-bold tracking-wider">Total Belanja</p>
                        <p class="text-lg font-bold text-white">Rp {{ number_format($total_contribution, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-5 border border-white/5 shadow-lg hover:bg-white/10 transition-colors">
                        <p class="text-[10px] text-teal-200 uppercase mb-2 font-bold tracking-wider">Poin Rewards</p>
                        <p class="text-lg font-bold text-white">0 Pts</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <x-mobile.bottom-nav active="home" />

    <div x-show="showCompletionModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-full opacity-0"
         class="fixed bottom-30 left-4 right-4 z-50 bg-white rounded-2xl shadow-2xl border border-gray-100 p-5"
         x-data="{ showCompletionModal: @entangle('show_completion_modal') }"
         style="display: none;">
        
        <div class="flex justify-between items-start mb-3">
            <div>
                <h4 class="text-sm font-bold text-gray-900">Lengkapi Profil Anda</h4>
                <p class="text-[10px] text-gray-500 mt-1">Data yang lengkap memudahkan verifikasi dan akses fitur.</p>
            </div>
            <button @click="showCompletionModal = false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        {{-- Progress Bar --}}
        <div class="mb-4">
            <div class="flex justify-between items-center mb-1">
                <span class="text-[10px] font-bold text-emerald-600">{{ $profile_completion }}% Selesai</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2">
                <div class="bg-emerald-500 h-2 rounded-full transition-all duration-1000" style="width: {{ $profile_completion }}%"></div>
            </div>
        </div>

        <a href="{{ route('member.profile') }}" class="block w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold text-center rounded-xl shadow-lg shadow-emerald-500/30 transition-all">
            Lengkapi Sekarang
        </a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
</div>