@use('SimpleSoftwareIO\QrCode\Facades\QrCode')

<div class="h-screen bg-gray-50 flex flex-col relative overflow-hidden font-sans">
    
    <div class="bg-emerald-800 px-5 pt-10 pb-6 shadow-lg shadow-emerald-900/20 z-40 flex-none relative">
        <div class="flex items-center gap-3 mb-5">
            <a href="{{ route('dashboard') }}" class="p-2 rounded-full bg-white/10 hover:bg-white/20 text-white transition-all -ml-2 backdrop-blur-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-xl font-bold text-white tracking-tight drop-shadow-sm">Profil & KTA</h1>
        </div>

        <div class="p-1 bg-emerald-500/40 backdrop-blur-md rounded-xl flex gap-1 border border-emerald-500/30">
            <button type="button" @click="$wire.set('activeTab', 'basic')" 
                class="flex-1 py-2.5 text-[10px] sm:text-xs font-bold rounded-lg transition-all duration-300
                {{ $activeTab == 'basic' ? 'bg-amber-400 text-emerald-700 shadow-md transform scale-[1.02]' : 'text-emerald-100 hover:text-white hover:bg-white/5' }}">
                Pribadi
            </button>
            <button type="button" @click="$wire.set('activeTab', 'economy')" 
                class="flex-1 py-2.5 text-[10px] sm:text-xs font-bold rounded-lg transition-all duration-300
                {{ $activeTab == 'economy' ? 'bg-amber-400 text-emerald-700 shadow-md transform scale-[1.02]' : 'text-emerald-100 hover:text-white hover:bg-white/5' }}">
                Ekonomi
            </button>
            <button type="button" @click="$wire.set('activeTab', 'kta')" 
                class="flex-1 py-2.5 text-[10px] sm:text-xs font-bold rounded-lg transition-all duration-300
                {{ $activeTab == 'kta' ? 'bg-amber-400 text-emerald-900 shadow-md transform scale-[1.02]' : 'text-emerald-100 hover:text-white hover:bg-white/5' }}">
                KTA Digital
            </button>
        </div>
    </div>  

    <div class="flex-1 overflow-y-auto bg-gradient-to-b from-emerald-800 via-gray-50 to-white relative w-full no-scrollbar">
        
        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-emerald-800 to-transparent pointer-events-none"></div>

        <div class="px-5 py-6 space-y-6 pb-32 relative z-10"> 
            
            @if($activeTab == 'basic')
                <div class="space-y-6 animate-fade-in">
                    
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xl shadow-emerald-900/5">
                        
                        <div class="flex flex-col items-center mb-6 -mt-10">
                            <div class="relative group cursor-pointer" onclick="document.getElementById('profile_photo').click()">
                                <div class="w-24 h-24 rounded-full bg-white p-1 shadow-lg transition-transform group-hover:scale-105">
                                    <div class="w-full h-full rounded-full overflow-hidden bg-emerald-100 relative">
                                        
                                        @if ($new_profile_photo)
                                            <img src="{{ $new_profile_photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                        
                                        @elseif ($member->image_url)
                                            <img src="{{ asset('storage/' . $member->image_url) }}" class="w-full h-full object-cover group-hover:opacity-80 transition-opacity">
                                        
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=059669&color=fff&size=128" class="w-full h-full object-cover group-hover:opacity-80 transition-opacity">
                                        @endif
                                        
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity rounded-full">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <input type="file" 
                                       id="profile_photo" 
                                       wire:model="new_profile_photo" 
                                       class="hidden" 
                                       accept="image/*"> 
                            </div>
                            <p class="text-[10px] font-bold text-emerald-600 mt-2 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100 uppercase tracking-wider">{{ $member->member_number }}</p>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 block ml-1">Nama Lengkap</label>
                                <input type="text" wire:model="name" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 font-semibold cursor-not-allowed focus:outline-none" readonly>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 block ml-1">Email</label>
                                <input type="email" wire:model="email" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed focus:outline-none" readonly>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Alamat Domisili</label>
                                <textarea wire:model="address" rows="3" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 text-gray-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all placeholder-gray-400 font-medium"></textarea>
                            </div>
                        </div>
                    </div>

                    @if($member->type == 'individual')
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-md">
                            <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-100">
                                <div class="p-1.5 bg-emerald-100 rounded-lg text-emerald-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900">Detail Pribadi</h3>
                            </div>
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 block ml-1">NIK</label>
                                    <input type="text" wire:model="nik" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 font-semibold cursor-not-allowed focus:outline-none tracking-widest" readonly>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Tempat Lahir</label>
                                        <input type="text" wire:model="place_of_birth" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-300 text-gray-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Tgl Lahir</label>
                                        <input type="date" wire:model="birth_date" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-300 text-gray-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Gender</label>
                                        <div class="relative">
                                            <select wire:model="gender" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-300 text-gray-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all appearance-none">
                                                <option value="m">Laki-laki</option>
                                                <option value="f">Perempuan</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">No WhatsApp</label>
                                        <input type="tel" wire:model="phone" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-300 text-gray-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all">
                                    </div>
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Nama Ibu Kandung</label>
                                    <input type="text" wire:model="mother_name" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-300 text-gray-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-md">
                            <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-100">
                                <div class="p-1.5 bg-blue-100 rounded-lg text-blue-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900">Dokumen Legalitas</h3>
                            </div>
                            
                            <div class="space-y-5">
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                    <div class="flex justify-between items-center mb-3">
                                        <label class="text-xs font-bold text-gray-500 uppercase">Foto KTP</label>
                                        <label for="ktp" class="text-[10px] font-bold text-emerald-600 bg-white border border-emerald-200 px-3 py-1.5 rounded-lg cursor-pointer hover:bg-emerald-50 shadow-sm transition-all">
                                            {{ $existing_ktp ? 'Ubah File' : '+ Upload' }}
                                        </label>
                                        <input type="file" id="ktp" wire:model="new_ktp" class="hidden">
                                    </div>
                                    <div class="h-40 w-full bg-white rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden relative group hover:border-emerald-400 transition-colors">
                                        @if($new_ktp)
                                            <img src="{{ $new_ktp->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover">
                                        @elseif($existing_ktp)
                                            <img src="{{ Storage::url($existing_ktp) }}" class="absolute inset-0 w-full h-full object-cover">
                                        @else
                                            <div class="text-center">
                                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <span class="text-xs text-gray-400 font-medium">Belum ada file</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                    <div class="flex justify-between items-center mb-3">
                                        <label class="text-xs font-bold text-gray-500 uppercase">Foto NPWP</label>
                                        <label for="npwp" class="text-[10px] font-bold text-emerald-600 bg-white border border-emerald-200 px-3 py-1.5 rounded-lg cursor-pointer hover:bg-emerald-50 shadow-sm transition-all">
                                            {{ $existing_npwp_ind ? 'Ubah File' : '+ Upload' }}
                                        </label>
                                        <input type="file" id="npwp" wire:model="new_npwp_ind" class="hidden">
                                    </div>
                                    <div class="h-24 w-full bg-white rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden relative group hover:border-emerald-400 transition-colors">
                                        @if($new_npwp_ind)
                                            <img src="{{ $new_npwp_ind->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover">
                                        @elseif($existing_npwp_ind)
                                            <img src="{{ Storage::url($existing_npwp_ind) }}" class="absolute inset-0 w-full h-full object-cover">
                                        @else
                                            <span class="text-xs text-gray-400 font-medium">Opsional</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($member->type == 'institution')
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-md">
                            <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-100">
                                <div class="p-1.5 bg-indigo-100 rounded-lg text-indigo-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900">Data Perusahaan</h3>
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 block ml-1">NIB</label>
                                    <input type="text" wire:model="nib" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed focus:outline-none" readonly>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">NPWP Perusahaan</label>
                                    <input type="text" wire:model="npwp" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-300 text-gray-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Tanggal Berdiri</label>
                                    <input type="date" wire:model="establishment_date" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-300 text-gray-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-md">
                            <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-100">
                                <div class="p-1.5 bg-orange-100 rounded-lg text-orange-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900">Penanggung Jawab (PIC)</h3>
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Nama Lengkap PIC</label>
                                    <input type="text" wire:model="pic_name" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-300 text-gray-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">No HP PIC</label>
                                        <input type="tel" wire:model="pic_phone" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-300 text-gray-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Jabatan</label>
                                        <input type="text" wire:model="pic_position" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-300 text-gray-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-md">
                            <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-100">
                                <div class="p-1.5 bg-blue-100 rounded-lg text-blue-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900">Dokumen Perusahaan</h3>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl bg-gray-50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-emerald-600 border border-gray-200 shadow-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-700">Dokumen NIB</p>
                                            <p class="text-[10px] text-gray-400">{{ $existing_nib ? 'File tersedia' : 'Belum ada file' }}</p>
                                        </div>
                                    </div>
                                    <label for="file_nib" class="text-[10px] font-bold text-white bg-emerald-500 hover:bg-emerald-600 px-3 py-1.5 rounded-lg cursor-pointer shadow-sm transition-all">
                                        Upload
                                        <input type="file" id="file_nib" wire:model="new_nib" class="hidden">
                                    </label>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="p-4 border border-gray-200 rounded-xl bg-gray-50 text-center">
                                        <p class="text-[10px] font-bold text-gray-500 mb-2 uppercase">AHU</p>
                                        <label for="file_ahu" class="block w-full py-3 border-2 border-dashed border-gray-300 rounded-xl bg-white cursor-pointer hover:border-emerald-400 transition-colors">
                                            <span class="text-xs text-emerald-600 font-bold">{{ $existing_ahu || $new_ahu ? 'Ganti File' : '+ Upload' }}</span>
                                            <input type="file" id="file_ahu" wire:model="new_ahu" class="hidden">
                                        </label>
                                    </div>
                                    <div class="p-4 border border-gray-200 rounded-xl bg-gray-50 text-center">
                                        <p class="text-[10px] font-bold text-gray-500 mb-2 uppercase">NPWP</p>
                                        <label for="file_npwp_inst" class="block w-full py-3 border-2 border-dashed border-gray-300 rounded-xl bg-white cursor-pointer hover:border-emerald-400 transition-colors">
                                            <span class="text-xs text-emerald-600 font-bold">{{ $existing_npwp_inst || $new_npwp_inst ? 'Ganti File' : '+ Upload' }}</span>
                                            <input type="file" id="file_npwp_inst" wire:model="new_npwp_inst" class="hidden">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            @endif


            @if($activeTab == 'economy')
                <div class="space-y-6 animate-fade-in">
                    
                    @if($member->type == 'individual')
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-md">
                            <div class="space-y-5">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Pekerjaan Utama</label>
                                    <div class="relative">
                                        <select wire:model.live="job_type" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-300 text-gray-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all appearance-none">
                                            <option value="">Pilih Pekerjaan...</option>
                                            <option value="petani">Petani</option>
                                            <option value="nelayan">Nelayan</option>
                                            <option value="peternak">Peternak</option>
                                            <option value="pedagang">Pedagang/UMKM</option>
                                            <option value="karyawan">Karyawan</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                                    </div>
                                </div>

                                @if(in_array($job_type, ['petani', 'nelayan', 'peternak']))
                                    <div>
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Komoditas Utama</label>
                                        <input type="text" wire:model="main_commodity" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-300 text-gray-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all" placeholder="Contoh: Padi, Jagung, Lele">
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if(in_array($job_type, ['petani', 'nelayan', 'peternak']))
                            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-md">
                                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-100">
                                    <div class="p-1.5 bg-emerald-100 rounded-lg text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-gray-900">Kapasitas Produksi</h3>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider mb-1 block ml-1">Lahan (Ha)</label>
                                        <input type="text" wire:model="prod_lahan" class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-emerald-200 text-gray-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 outline-none transition-all text-center">
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider mb-1 block ml-1">Panen (Ton)</label>
                                        <input type="text" wire:model="prod_panen" class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-emerald-200 text-gray-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 outline-none transition-all text-center">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider mb-1 block ml-1">Siklus Panen (Bulan)</label>
                                        <input type="text" wire:model="prod_siklus" class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-emerald-200 text-gray-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 outline-none transition-all text-center">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-md">
                            <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-100">
                                <div class="p-1.5 bg-orange-100 rounded-lg text-orange-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900">Belanja Rutin (Bulanan)</h3>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Beras (Kg)</label>
                                    <input type="text" wire:model="cons_beras" class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-gray-900 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/10 outline-none transition-all text-center">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Minyak (Liter)</label>
                                    <input type="text" wire:model="cons_minyak" class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-gray-900 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/10 outline-none transition-all text-center">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Gula (Kg)</label>
                                    <input type="text" wire:model="cons_gula" class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-gray-900 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/10 outline-none transition-all text-center">
                                </div>
                                @if($job_type == 'petani')
                                    <div>
                                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Pupuk (Kg)</label>
                                        <input type="text" wire:model="cons_pupuk" class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-gray-900 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/10 outline-none transition-all text-center">
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($member->type == 'institution')
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-md space-y-5">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Peran Rantai Pasok</label>
                                <div class="relative">
                                    <select wire:model="supply_chain_role" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-300 text-gray-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all appearance-none">
                                        <option value="produsen">Produsen</option>
                                        <option value="distributor">Distributor</option>
                                        <option value="retailer">Retailer</option>
                                        <option value="logistik">Logistik</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Jumlah Anggota</label>
                                    <input type="number" wire:model="total_members" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-300 text-gray-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all text-center">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Omset (Rp)</label>
                                    <input type="text" wire:model="annual_turnover" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-300 text-gray-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all text-center">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-md">
                            <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-4">
                                <div class="flex items-center gap-2">
                                    <div class="p-1.5 bg-blue-100 rounded-lg text-blue-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-gray-900">Aset Logistik</h3>
                                </div>
                                <button type="button" wire:click="addLogistic" class="text-[10px] bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg shadow-sm font-bold transition-all flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Tambah
                                </button>
                            </div>
                            
                            @foreach($logistics as $index => $item)
                                <div class="flex gap-3 mb-3 items-end animate-fade-in">
                                    <div class="w-1/2">
                                        <label class="text-[10px] text-gray-400 font-bold uppercase mb-1 block pl-1">Jenis</label>
                                        <input type="text" wire:model="logistics.{{ $index }}.key" placeholder="Truk/Gudang" class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-gray-900 text-xs focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all">
                                    </div>
                                    <div class="w-1/3">
                                        <label class="text-[10px] text-gray-400 font-bold uppercase mb-1 block pl-1">Kap.</label>
                                        <input type="text" wire:model="logistics.{{ $index }}.value" placeholder="Ton/Unit" class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-gray-900 text-xs focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all">
                                    </div>
                                    <button type="button" wire:click="removeLogistic({{ $index }})" class="p-2.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-transparent hover:border-red-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            @endif

            @if($activeTab == 'kta')
                <div class="space-y-6 animate-fade-in flex flex-col items-center justify-center pt-4">
                    
                    <div class="relative w-full max-w-[340px] aspect-[1.586/1] bg-gradient-to-br from-emerald-600 to-teal-800 rounded-2xl shadow-2xl overflow-hidden text-white transform transition-transform hover:scale-[1.02] duration-300">
                        
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 opacity-10 transform rotate-12">
                            <img src="{{ asset('images/logoElemen.png') }}" class="w-full h-full object-contain">
                        </div>
                        
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10"></div>

                        <div class="absolute top-4 left-4 right-4 flex justify-between items-start z-10">
                            <div class="flex items-center gap-2">
                                <img src="{{ asset('images/logoElemen.png') }}" class="w-8 h-8 object-contain drop-shadow-sm">
                                <div>
                                    <h3 class="text-[10px] font-bold uppercase tracking-widest leading-none text-emerald-100">Koperasi</h3>
                                    <h2 class="text-sm font-black uppercase tracking-wide leading-none text-white mt-0.5">Fanantara</h2>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[8px] font-bold bg-amber-400 text-emerald-900 uppercase tracking-wider shadow-sm">
                                Member
                            </span>
                        </div>

                        <div class="absolute top-20 left-4 right-4 bottom-4 flex items-end justify-between z-10">
                            <div class="flex-1 pr-2">
                                <div class="mb-3">
                                    <p class="text-[8px] text-emerald-200 uppercase tracking-widest mb-0.5">Nama Anggota</p>
                                    <h4 class="text-sm font-bold text-amber-300 uppercase tracking-wide leading-tight line-clamp-1 drop-shadow-sm">
                                        {{ $name }}
                                    </h4>
                                </div>
                                <div class="flex gap-4">
                                    <div>
                                        <p class="text-[7px] text-emerald-200 uppercase tracking-widest mb-0.5">ID Anggota</p>
                                        <p class="text-[10px] font-mono font-bold text-white tracking-wider">{{ $member->member_number }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[7px] text-emerald-200 uppercase tracking-widest mb-0.5">Bergabung</p>
                                        <p class="text-[10px] font-bold text-white">{{ $member->created_at->format('M Y') }}</p>
                                    </div>
                                </div>
                                <div class="mt-2 pt-2 border-t border-white/10">
                                    <p class="text-[8px] text-emerald-100 leading-tight opacity-80 line-clamp-3">
                                        {{ $full_address ?? 'Alamat belum dilengkapi' }}
                                    </p>
                                </div>
                            </div>

                            <div class="bg-white p-1 rounded-lg shadow-lg">
                                {!! QrCode::size(60)->margin(0)->generate($member->member_number) !!}
                            </div>
                        </div>
                    </div>

                    <div class="w-full max-w-[340px] grid grid-cols-2 gap-3 mt-2">
                        <a href="{{ route('print.card', $member->id) }}" target="_blank" class="flex items-center justify-center gap-2 py-3 bg-emerald-600 text-white rounded-xl font-bold text-xs shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12"></path></svg>
                            Download PDF
                        </a>
                        <button class="flex items-center justify-center gap-2 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold text-xs shadow-sm hover:bg-gray-50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                            Share KTA
                        </button>
                    </div>

                    <div class="text-center px-6">
                        <p class="text-[10px] text-gray-400">Tunjukkan QR Code ini kepada petugas koperasi untuk verifikasi identitas atau absensi kegiatan.</p>
                    </div>

                </div>
            @endif

        </div> 

        @if($activeTab !== 'kta')
            <div class="fixed bottom-0 left-0 right-0 p-5 bg-white/95 backdrop-blur-md border-t border-gray-100 max-w-md mx-auto z-50">
                <button type="button" wire:click="save" wire:loading.attr="disabled" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all flex justify-center items-center gap-2 transform active:scale-[0.98]">
                    
                    <div class="flex items-center gap-2" wire:loading.remove>
                        <span>Simpan Perubahan</span>
                    </div>

                    <div class="flex items-center gap-2" wire:loading>
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Menyimpan...</span>
                    </div>

                </button>
            </div>
        @endif

    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</div>