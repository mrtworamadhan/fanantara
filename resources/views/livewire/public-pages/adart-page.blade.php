<div>
    <!-- Hero Header - Mobile First -->
    <section class="pt-24 pb-6 md:pt-28 md:pb-8 bg-gradient-to-br from-emerald-50 via-white to-purple-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full mb-3">
                <i class="bi bi-file-earmark-text-fill"></i>
                Dokumen Resmi
            </span>
            <h1 class="text-xl md:text-3xl font-black text-gray-900 mb-2">
                AD/ART <span class="text-gradient">Fanantara</span>
            </h1>
            <p class="text-gray-600 text-sm max-w-md mx-auto">
                Anggaran Dasar & Anggaran Rumah Tangga Koperasi
            </p>
        </div>
    </section>

    <!-- PDF Viewer Section -->
    <section class="py-6 md:py-10 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Document Card -->
            <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                <!-- Header -->
                <div class="p-4 md:p-5 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center shrink-0">
                                <i class="bi bi-file-earmark-pdf-fill text-red-500 text-lg"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-gray-900 text-sm truncate">AD/ART Koperasi Fanantara</h3>
                                <p class="text-xs text-gray-500">Dokumen PDF</p>
                            </div>
                        </div>
                        <a href="{{ asset('documents/adart.pdf') }}" 
                           target="_blank"
                           class="shrink-0 inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-700 transition-colors">
                            <i class="bi bi-box-arrow-up-right"></i>
                            <span class="hidden sm:inline">Buka PDF</span>
                        </a>
                    </div>
                </div>

                <!-- PDF Viewer Container -->
                <div id="pdf-container" class="bg-gray-200 relative">
                    <!-- Loading -->
                    <div id="pdf-loading" class="flex items-center justify-center py-20">
                        <div class="text-center">
                            <div class="w-10 h-10 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
                            <p class="text-sm text-gray-500">Memuat dokumen...</p>
                        </div>
                    </div>

                    <!-- PDF Pages akan di-render disini -->
                    <div id="pdf-pages" class="hidden flex-col items-center gap-3 p-3 max-h-[70vh] overflow-y-auto"></div>

                    <!-- Error State -->
                    <div id="pdf-error" class="hidden flex flex-col items-center justify-center py-16 px-4 text-center">
                        <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mb-4">
                            <i class="bi bi-exclamation-triangle-fill text-red-500 text-2xl"></i>
                        </div>
                        <p class="text-gray-700 font-medium mb-1">Gagal memuat dokumen</p>
                        <p class="text-gray-500 text-sm mb-4">Silakan buka PDF secara langsung</p>
                        <a href="{{ asset('documents/adart.pdf') }}" target="_blank" 
                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-lg hover:bg-emerald-700">
                            <i class="bi bi-box-arrow-up-right"></i> Buka PDF
                        </a>
                    </div>

                    <!-- Controls -->
                    <div id="pdf-controls" class="hidden bg-white border-t border-gray-200 p-3">
                        <div class="flex items-center justify-center gap-3 text-sm">
                            <span class="text-gray-600">Halaman <span id="pdf-current">1</span> dari <span id="pdf-total">1</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-amber-50 border border-amber-100 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 bg-amber-100 rounded-lg flex items-center justify-center shrink-0">
                        <i class="bi bi-info-circle-fill text-amber-600"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm mb-1">Tentang AD/ART</h4>
                        <p class="text-gray-600 text-xs leading-relaxed">
                            Anggaran Dasar dan Anggaran Rumah Tangga mengatur struktur organisasi, hak dan kewajiban anggota, serta mekanisme pengambilan keputusan. Setiap calon anggota wajib membaca dan menyetujui AD/ART sebelum bergabung.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    @assets
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    @endassets

    @script
    <script>
        const pdfUrl = "{{ asset('documents/adart.pdf') }}";
        const pagesContainer = document.getElementById('pdf-pages');
        const loadingEl = document.getElementById('pdf-loading');
        const errorEl = document.getElementById('pdf-error');
        const controlsEl = document.getElementById('pdf-controls');

        // Set worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        // Load PDF
        pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
            loadingEl.classList.add('hidden');
            pagesContainer.classList.remove('hidden');
            pagesContainer.classList.add('flex');
            controlsEl.classList.remove('hidden');
            
            document.getElementById('pdf-total').textContent = pdf.numPages;

            // Render semua halaman
            for (let i = 1; i <= pdf.numPages; i++) {
                renderPage(pdf, i);
            }
        }).catch(function(error) {
            console.error('PDF Error:', error);
            loadingEl.classList.add('hidden');
            errorEl.classList.remove('hidden');
            errorEl.classList.add('flex');
        });

        function renderPage(pdf, pageNum) {
            pdf.getPage(pageNum).then(function(page) {
                const containerWidth = pagesContainer.clientWidth - 24;
                const viewport = page.getViewport({ scale: 1.0 });
                const scale = containerWidth / viewport.width;
                const scaledViewport = page.getViewport({ scale: scale });

                const canvas = document.createElement('canvas');
                canvas.className = 'bg-white shadow rounded-lg';
                canvas.width = scaledViewport.width;
                canvas.height = scaledViewport.height;
                
                pagesContainer.appendChild(canvas);

                page.render({
                    canvasContext: canvas.getContext('2d'),
                    viewport: scaledViewport
                });
            });
        }

        // Track halaman saat scroll
        pagesContainer.addEventListener('scroll', function() {
            const canvases = pagesContainer.querySelectorAll('canvas');
            const containerTop = pagesContainer.getBoundingClientRect().top;
            
            canvases.forEach((canvas, index) => {
                const rect = canvas.getBoundingClientRect();
                if (rect.top <= containerTop + 100 && rect.bottom >= containerTop) {
                    document.getElementById('pdf-current').textContent = index + 1;
                }
            });
        });
    </script>
    @endscript
</div>
