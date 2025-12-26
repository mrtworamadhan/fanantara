<div>
    <!-- Hero Header -->
    <section class="pt-24 pb-6 md:pt-28 md:pb-8 bg-gradient-to-br from-emerald-50 via-white to-purple-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 mb-2">
                Hubungi <span class="text-gradient">Kami</span>
            </h1>
            <p class="text-gray-600 text-sm max-w-md mx-auto">
                Ada pertanyaan? Silakan hubungi kami atau isi form di bawah
            </p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-10 md:py-14 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8">
                
                <!-- Contact Info -->
                <div class="space-y-5">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Kontak</h2>
                    
                    @php
                        $whatsapp = \App\Models\SiteSetting::get('contact_whatsapp', '6281234567890');
                        $email = \App\Models\SiteSetting::get('contact_email', 'info@fanantara.com');
                        $address = \App\Models\SiteSetting::get('contact_address', 'Jl. Contoh Alamat No. 123, Kota, Provinsi 12345');
                        $whatsappFormatted = '+' . substr($whatsapp, 0, 2) . ' ' . substr($whatsapp, 2, 3) . ' ' . substr($whatsapp, 5, 4) . ' ' . substr($whatsapp, 9);
                    @endphp

                    <!-- WhatsApp -->
                    <a href="https://wa.me/{{ $whatsapp }}" target="_blank" class="flex items-center gap-4 p-4 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors group">
                        <div class="w-11 h-11 bg-emerald-500 rounded-lg flex items-center justify-center">
                            <i class="bi bi-whatsapp text-white text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">WhatsApp</p>
                            <p class="font-semibold text-gray-900 group-hover:text-emerald-600">{{ $whatsappFormatted }}</p>
                        </div>
                    </a>

                    <!-- Email -->
                    <a href="mailto:{{ $email }}" class="flex items-center gap-4 p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors group">
                        <div class="w-11 h-11 bg-purple-500 rounded-lg flex items-center justify-center">
                            <i class="bi bi-envelope-fill text-white text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Email</p>
                            <p class="font-semibold text-gray-900 group-hover:text-purple-600">{{ $email }}</p>
                        </div>
                    </a>

                    <!-- Address -->
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl">
                        <div class="w-11 h-11 bg-gray-500 rounded-lg flex items-center justify-center shrink-0">
                            <i class="bi bi-geo-alt-fill text-white text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Alamat Kantor</p>
                            <p class="font-semibold text-gray-900 text-sm leading-relaxed">
                                {!! nl2br(e($address)) !!}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-gray-50 p-6 rounded-2xl">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Kirim Pesan</h2>
                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm" placeholder="Nama lengkap">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm" placeholder="email@contoh.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subjek</label>
                            <input type="text" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm" placeholder="Subjek pesan">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
                            <textarea rows="4" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm resize-none" placeholder="Tulis pesan Anda..."></textarea>
                        </div>
                        <button type="submit" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 transition-colors">
                            <i class="bi bi-send-fill mr-2"></i>Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
