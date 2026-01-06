<div class="relative min-h-screen w-full overflow-hidden bg-gray-900" x-data="{ 
        splash: true, 
        showPassword: false 
     }" x-init="setTimeout(() => splash = false, 2500)">

    <div
        x-show="splash"
        x-transition:leave="transition ease-in duration-700"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-110"
        class="fixed inset-0 z-50 flex flex-col items-center justify-center
            bg-cover bg-center"
        style="background-image: url('{{ asset('images/banner2.png') }}')"
    >
        <div class="absolute inset-0 bg-white/60 backdrop-blur-sm"></div>

        <div class="relative z-10 flex flex-col items-center">

            <div class="relative flex items-center justify-center">
                <div class="absolute w-32 h-32 bg-emerald-300 rounded-full animate-ping opacity-40"></div>

                <div
                    class="relative w-24 h-24 bg-white rounded-full shadow-xl
                        flex items-center justify-center z-10"
                >
                    <img
                        src="{{ asset('images/logoElemen.png') }}"
                        alt="Logo"
                        class="w-12 h-12 object-contain"
                    />
                </div>
            </div>

            <h1 class="mt-6 text-2xl font-bold text-blue-600 tracking-tight animate-pulse">
                FANANTARA
            </h1>
            <h2 class="mt-1 text-lg font-semibold text-red-600 tracking-tight animate-pulse">
                KOPERASI MULTIPIHAK
            </h2>

            <p class="text-sm text-gray-600 mt-4">
                Koperasi Modern Masa Depan
            </p>
        </div>
    </div>


    <style>
        .gradient-primary {
            background: linear-gradient(
                135deg,
                #22c55e 0%,
                #16a34a 50%,
                #15803d 100%
            );
        }
        .btn-primary {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .btn-primary::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.3),
                transparent
            );
            transition: left 0.5s ease;
        }
        .btn-primary:hover::before {
            left: 100%;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(34, 197, 94, 0.3);
        }
    </style>

    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent z-10"></div>

        <img src="{{ asset('images/banner1.png') }}"
            class="w-full h-full object-cover object-left"
            alt="Background Banner">
    </div>

    <div class="absolute top-8 left-0 right-0 z-20 px-6">
        <div class="flex justify-center mb-3">
                <div
                    class="w-25 h-25 bg-white/20 backdrop-blur-md
                        rounded-2xl flex items-center justify-center
                        border border-white/30 shadow-lg">
                    <img
                        src="{{ asset('images/logoElemen.png') }}"
                        alt="Logo"
                        class="w-20 h-20 object-contain"
                    />
                </div>
            </div>
        <div class="mx-auto max-w-sm
                    rounded-2xl
                    bg-white/35 backdrop-blur-md
                    border border-white/20
                    px-6 py-4
                    shadow-xl text-center">

            <h2 class="text-3xl font-bold text-blue-600 tracking-tight drop-shadow-lg">
                FANANTARA
            </h2>
            <h3 class="text-xl font-semibold text-red-600 tracking-tight drop-shadow-lg">
                KOPERASI MULTIPIHAK
            </h3>
            <p class="text-black/90 text-sm mt-1 drop-shadow-md">
                Supply Chain Integration Ecosystem
            </p>
        </div>
    </div>


    <div class="absolute bottom-0 left-0 right-0 z-30">
        <div class="bg-white rounded-t-[2.5rem] p-8 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.3)]">

            <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-6"></div> 

            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900">Selamat Datang! 👋</h3>
                <p class="text-gray-500 text-sm">Silakan masuk ke akun anggota Anda.</p>
            </div>

            <form wire:submit.prevent="login" class="space-y-5">

                <div class="space-y-1">
                    <label class="text-sm font-medium text-gray-700 ml-1">Email</label>
                    <div class="relative">
                        <input wire:model="email" type="email" required placeholder="member@fanantara.id"
                            class="w-full pl-4 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-gray-900 placeholder-gray-400">
                    </div>
                    @error('email') <span class="text-red-500 text-xs ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="text-sm font-medium text-gray-700 ml-1">Password</label>
                    <div class="relative">
                        <input wire:model="password" :type="showPassword ? 'text' : 'password'" required
                            placeholder="••••••••"
                            class="w-full pl-4 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-gray-900 placeholder-gray-400">

                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1">
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
                    @error('password') <span class="text-red-500 text-xs ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <a href="#" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lupa Password?</a>
                </div>

                <div class="space-y-3">
                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full flex justify-center items-center py-3.5 px-4 rounded-xl text-white font-semibold btn-primary gradient-primary shadow-lg shadow-emerald-500/30 transition-all transform active:scale-95">
                        <span wire:loading.remove>Masuk Sekarang</span>
                        <span wire:loading><svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg> Memproses...</span>
                    </button>

                    <a href="{{ route('login.google') }}"
                        class="w-full flex justify-center items-center py-3.5 px-4 rounded-xl border border-gray-200 bg-white text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        <img class="h-5 w-5 mr-3" src="https://www.svgrepo.com/show/475656/google-color.svg"
                            alt="Google">
                        Masuk dengan Google
                    </a>
                </div>

                <p class="text-center text-sm text-gray-500 mt-4">
                    Belum menjadi anggota?
                    <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:underline">Daftar Akun</a>
                </p>

            </form>
        </div>
    </div>
    <x-ui.notif />
</div>