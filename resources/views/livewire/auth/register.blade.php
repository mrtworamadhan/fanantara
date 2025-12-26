<div class="relative min-h-screen w-full overflow-hidden bg-gray-900" x-data="{ showPassword: false }">

    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent z-10"></div>
        <img src="{{ asset('images/banner2.png') }}"
            onerror="this.src='https://images.unsplash.com/photo-1556761175-5973dc0f32e7?q=80&w=1632&auto=format&fit=crop'"
            class="w-full h-full object-cover object-center">
    </div>

    <div class="absolute top-8 left-0 right-0 z-20 px-6">
        <h2 class="text-3xl font-bold text-white">Daftar Anggota</h2>
        <p class="text-white/80 text-sm">Bergabung dengan ekosistem kami.</p>
    </div>

    <div class="absolute bottom-0 left-0 right-0 z-30 h-[85vh] flex flex-col">

        <div
            class="bg-white rounded-t-[2.5rem] shadow-2xl flex-1 flex flex-col w-full max-w-md mx-auto relative overflow-hidden">

            <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mt-4 mb-2 shrink-0"></div>

            <div class="flex-1 overflow-y-auto px-6 pb-32 custom-scrollbar">

                <div
                    class="flex items-center justify-between mb-6 mt-2 sticky top-0 bg-white z-10 py-4 border-b border-gray-100">
                    <div class="flex gap-2">
                        <div class="h-2 w-8 rounded-full {{ $currentStep >= 1 ? 'bg-emerald-600' : 'bg-gray-200' }}">
                        </div>
                        <div class="h-2 w-8 rounded-full {{ $currentStep >= 2 ? 'bg-emerald-600' : 'bg-gray-200' }}">
                        </div>
                    </div>
                    <span class="text-xs font-bold text-gray-500">Langkah {{ $currentStep }}/2</span>
                </div>

                @if($currentStep == 1)
                    <div class="space-y-6 animate-fade-in-up">
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Jenis Keanggotaan</h3>
                            <p class="text-gray-500 text-sm">Siapa yang mendaftar?</p>
                        </div>

                        <label class="relative block cursor-pointer group">
                            <input type="radio" wire:model.live="account_type" value="individual" class="peer sr-only">
                            <div
                                class="p-5 rounded-2xl border-2 border-gray-100 bg-gray-50 hover:bg-white peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all shadow-sm">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-full bg-white text-emerald-600 flex items-center justify-center shadow-sm peer-checked:bg-emerald-600 peer-checked:text-white transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900">Individu</h4>
                                        <p class="text-xs text-gray-500 mt-1">Perorangan</p>
                                    </div>
                                    <div
                                        class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-900 transition-all">
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="relative block cursor-pointer group">
                            <input type="radio" wire:model.live="account_type" value="institution" class="peer sr-only">
                            <div
                                class="p-5 rounded-2xl border-2 border-gray-100 bg-gray-50 hover:bg-white peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all shadow-sm">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-full bg-white text-indigo-600 flex items-center justify-center shadow-sm peer-checked:bg-indigo-600 peer-checked:text-white transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900">Institusi</h4>
                                        <p class="text-xs text-gray-500 mt-1">Koperasi / Badan Usaha</p>
                                    </div>
                                    <div
                                        class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-900 transition-all">
                                    </div>
                                </div>
                            </div>
                        </label>

                        <button wire:click="nextStep"
                            class="w-full py-3.5 rounded-xl font-bold text-white bg-emerald-600 shadow-lg mt-4 hover:bg-emerald-700 transition-all">
                            Lanjut Isi Data
                        </button>
                    </div>
                @endif

                @if($currentStep == 2)
                    <div class="space-y-8 animate-fade-in-up">

                        <div class="space-y-5">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-1 h-6 bg-emerald-500 rounded-full"></div>
                                <h3 class="text-lg font-bold text-gray-900">Data Identitas</h3>
                            </div>

                            <div>
                                <label class="text-xs font-bold uppercase text-gray-500 mb-1.5 block tracking-wider">
                                    {{ $account_type == 'individual' ? 'Nama Lengkap' : 'Nama Perusahaan' }}
                                </label>
                                <input wire:model="name" type="text"
                                    class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all placeholder-gray-400">
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="text-xs font-bold uppercase text-gray-500 mb-1.5 block tracking-wider">
                                    {{ $account_type == 'individual' ? 'NIK (16 Digit)' : 'NIB (Nomor Induk Berusaha)' }}
                                </label>
                                <input wire:model="identity_no" type="number"
                                    class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                                @error('identity_no') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            @if($account_type == 'individual')
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="text-xs font-bold uppercase text-gray-500 mb-1.5 block tracking-wider">Tempat
                                            Lahir</label>
                                        <input wire:model="place_of_birth" type="text"
                                            class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none transition-all">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold uppercase text-gray-500 mb-1.5 block tracking-wider">Tgl
                                            Lahir</label>
                                        <input wire:model="birth_date" type="date"
                                            class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none transition-all">
                                        @error('birth_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="text-xs font-bold uppercase text-gray-500 mb-1.5 block tracking-wider">Gender</label>
                                        <select wire:model="gender"
                                            class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none transition-all">
                                            <option value="m">Laki-laki</option>
                                            <option value="f">Perempuan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold uppercase text-gray-500 mb-1.5 block tracking-wider">No
                                            HP (WA)</label>
                                        <input wire:model="phone_individual" type="tel"
                                            class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none transition-all">
                                        @error('phone_individual') <span
                                        class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-bold uppercase text-gray-500 mb-1.5 block tracking-wider">Nama
                                        Ibu Kandung</label>
                                    <input wire:model="mother_name" type="text"
                                        class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none transition-all">
                                </div>
                                <div>
                                    <label
                                        class="text-xs font-bold uppercase text-gray-500 mb-1.5 block tracking-wider">Pekerjaan</label>
                                    <input wire:model="job_type" type="text"
                                        class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none transition-all">
                                </div>
                            @endif

                            @if($account_type == 'institution')
                                <div>
                                    <label class="text-xs font-bold uppercase text-gray-500 mb-1.5 block tracking-wider">NPWP
                                        Perusahaan</label>
                                    <input wire:model="npwp" type="text"
                                        class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none transition-all">
                                </div>
                                <div>
                                    <label class="text-xs font-bold uppercase text-gray-500 mb-1.5 block tracking-wider">Tanggal
                                        Berdiri</label>
                                    <input wire:model="establishment_date" type="date"
                                        class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none transition-all">
                                </div>
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 space-y-3">
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Data PIC</p>
                                    <input wire:model="pic_name" type="text" placeholder="Nama PIC"
                                        class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none transition-all">
                                    @error('pic_name') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror
                                    <div class="grid grid-cols-2 gap-3">
                                        <input wire:model="pic_phone" type="tel" placeholder="HP PIC"
                                            class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none transition-all">
                                        <input wire:model="pic_position" type="text" placeholder="Jabatan"
                                            class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none transition-all">
                                    </div>
                                    @error('pic_phone') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror
                                </div>
                            @endif
                        </div>

                        <div class="space-y-5">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-1 h-6 bg-emerald-500 rounded-full"></div>
                                <h3 class="text-lg font-bold text-gray-900">Dokumen Legalitas</h3>
                            </div>

                            @if($account_type == 'individual')
                                <div>
                                    <label class="text-xs font-bold uppercase text-gray-500 mb-2 block tracking-wider">Foto KTP
                                        (Wajib)</label>
                                    <label for="file_ktp"
                                        class="flex flex-col items-center justify-center w-full h-36 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-emerald-50 transition-all relative overflow-hidden">
                                        @if ($file_ktp)
                                            <img src="{{ $file_ktp->temporaryUrl() }}"
                                                class="absolute inset-0 w-full h-full object-cover opacity-90">
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/40"><span
                                                    class="text-white text-xs font-bold px-3 py-1 bg-black/50 rounded-full">Ganti
                                                    File</span></div>
                                        @else
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-gray-400">
                                                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                                    </path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <p class="text-xs font-medium">Ketuk Upload KTP</p>
                                            </div>
                                        @endif
                                        <input id="file_ktp" wire:model="file_ktp" type="file" class="hidden"
                                            accept="image/*" />
                                    </label>
                                    @error('file_ktp') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <label for="file_npwp_ind"
                                    class="flex items-center justify-between px-4 py-3 border border-gray-300 rounded-xl cursor-pointer bg-white">
                                    <span class="text-sm text-gray-600 truncate flex-1">@if($file_npwp_ind) <span
                                    class="text-emerald-600 font-bold">File Terpilih</span> @else Upload NPWP (Opsional)
                                            @endif</span>
                                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded">Browse</span>
                                    <input id="file_npwp_ind" wire:model="file_npwp_ind" type="file" class="hidden"
                                        accept="image/*" />
                                </label>
                            @endif

                            @if($account_type == 'institution')
                                <div>
                                    <label class="text-xs font-bold uppercase text-gray-500 mb-2 block tracking-wider">Scan NIB
                                        (Wajib)</label>
                                    <label for="file_nib"
                                        class="flex flex-col items-center justify-center w-full h-36 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-emerald-50 transition-all relative overflow-hidden">
                                        @if ($file_nib)
                                            <img src="{{ $file_nib->temporaryUrl() }}"
                                                class="absolute inset-0 w-full h-full object-cover opacity-90">
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/40"><span
                                                    class="text-white text-xs font-bold px-3 py-1 bg-black/50 rounded-full">Ganti
                                                    File</span></div>
                                        @else
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-gray-400">
                                                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                                <p class="text-xs font-medium">Upload NIB</p>
                                            </div>
                                        @endif
                                        <input id="file_nib" wire:model="file_nib" type="file" class="hidden"
                                            accept="image/*" />
                                    </label>
                                    @error('file_nib') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <label for="file_ahu"
                                        class="flex flex-col items-center justify-center h-20 border border-gray-200 rounded-xl cursor-pointer bg-white">
                                        <span class="text-[10px] font-bold uppercase text-gray-400 mb-1">AHU (Opsional)</span>
                                        @if ($file_ahu) <span class="text-xs text-emerald-600 font-bold">Terpilih</span> @else
                                        <span class="text-xs text-gray-400">+ Upload</span> @endif
                                        <input id="file_ahu" wire:model="file_ahu" type="file" class="hidden"
                                            accept="image/*" />
                                    </label>
                                    <label for="file_npwp_inst"
                                        class="flex flex-col items-center justify-center h-20 border border-gray-200 rounded-xl cursor-pointer bg-white">
                                        <span class="text-[10px] font-bold uppercase text-gray-400 mb-1">NPWP (Opsional)</span>
                                        @if ($file_npwp_inst) <span class="text-xs text-emerald-600 font-bold">Terpilih</span>
                                        @else <span class="text-xs text-gray-400">+ Upload</span> @endif
                                        <input id="file_npwp_inst" wire:model="file_npwp_inst" type="file" class="hidden"
                                            accept="image/*" />
                                    </label>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-5">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-1 h-6 bg-emerald-500 rounded-full"></div>
                                <h3 class="text-lg font-bold text-gray-900">Alamat & Persetujuan</h3>
                            </div>

                            <div><select wire:model.live="province_code"
                                    class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none">
                                    <option value="">Pilih Provinsi</option>@foreach($provinces as $code => $name)<option
                                    value="{{ $code }}">{{ $name }}</option>@endforeach
                                </select></div>
                            <div><select wire:model.live="city_code"
                                    class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none"
                                    @if(empty($cities)) disabled @endif>
                                    <option value="">Pilih Kota/Kab</option>@foreach($cities as $code => $name)<option
                                    value="{{ $code }}">{{ $name }}</option>@endforeach
                                </select></div>
                            <div class="grid grid-cols-2 gap-4">
                                <select wire:model.live="district_code"
                                    class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none"
                                    @if(empty($districts)) disabled @endif>
                                    <option value="">Kecamatan</option>@foreach($districts as $code => $name)<option
                                    value="{{ $code }}">{{ $name }}</option>@endforeach
                                </select>
                                <select wire:model.live="village_code"
                                    class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none"
                                    @if(empty($villages)) disabled @endif>
                                    <option value="">Desa/Kel</option>@foreach($villages as $code => $name)<option
                                    value="{{ $code }}">{{ $name }}</option>@endforeach
                                </select>
                            </div>
                            <div>
                                <textarea wire:model="street_address" rows="3"
                                    class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none transition-all"
                                    placeholder="Nama Jalan, RT/RW, No Rumah..."></textarea>
                                @error('street_address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div wire:ignore x-data="{
                                    signaturePad: null,
                                    init() { this.signaturePad = new SignaturePad(this.$refs.canvas, { backgroundColor: 'rgb(255, 255, 255)', penColor: 'rgb(0, 0, 0)' }); },
                                    clear() { this.signaturePad.clear(); $wire.set('digital_signature', null); },
                                    save() { if (this.signaturePad.isEmpty()) { alert('Isi tanda tangan!'); } else { $wire.set('digital_signature', this.signaturePad.toDataURL('image/png')); alert('TTD Tersimpan!'); } }
                                }">
                                <label class="text-xs font-bold uppercase text-gray-500 mb-2 block tracking-wider">Tanda
                                    Tangan Digital</label>
                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-xl overflow-hidden touch-none relative bg-gray-50 h-40">
                                    <canvas x-ref="canvas" class="w-full h-full cursor-crosshair"></canvas>
                                    <div class="absolute bottom-2 right-2 flex gap-2">
                                        <button type="button" @click="clear()"
                                            class="text-xs bg-gray-200 px-3 py-1.5 rounded font-bold text-gray-600">Hapus</button>
                                        <button type="button" @click="save()"
                                            class="text-xs bg-emerald-600 px-3 py-1.5 rounded font-bold text-white shadow">Simpan</button>
                                    </div>
                                </div>
                                @error('digital_signature') <span class="text-red-500 text-xs block mt-1">Wajib diisi &
                                disimpan</span> @enderror
                            </div>
                        </div>

                        <div
                            x-data="{
                                showPassword: false,
                                showConfirm: false
                            }"
                            class="space-y-4 pt-4 border-t border-gray-100"
                        >
                            <h3 class="text-lg font-bold text-gray-900">Buat Password</h3>

                            <input
                                wire:model="email"
                                type="email"
                                placeholder="Email"
                                class="w-full px-4 py-3 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none transition-all">

                            @error('email')
                                <span class="text-red-500 text-xs block">{{ $message }}</span>
                            @enderror

                            <div class="relative">
                                <input
                                    wire:model="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    placeholder="Password"
                                    class="w-full px-4 py-3 pr-12 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none transition-all">

                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                >
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showPassword" style="display: none;" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>

                            @error('password')
                                <span class="text-red-500 text-xs block">{{ $message }}</span>
                            @enderror

                            <div class="relative">
                                <input
                                    wire:model="password_confirmation"
                                    :type="showConfirm ? 'text' : 'password'"
                                    placeholder="Ulangi Password"
                                    class="w-full px-4 py-3 pr-12 rounded-xl bg-white border border-gray-300 focus:border-emerald-500 outline-none transition-all">

                                <button
                                    type="button"
                                    @click="showConfirm = !showConfirm"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                >
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showPassword" style="display: none;" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>


                        <div class="flex gap-3 pt-6 pb-2">
                            <button wire:click="prevStep"
                                class="w-1/3 py-3 rounded-xl font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-all">Kembali</button>
                            <button wire:click="register" wire:loading.attr="disabled"
                                class="w-2/3 py-3 rounded-xl font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-lg transition-all flex justify-center items-center">
                                <span wire:loading.remove>Daftar Sekarang</span>
                                <span wire:loading>Memproses...</span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
</div>