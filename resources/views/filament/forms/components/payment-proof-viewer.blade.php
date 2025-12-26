<div class="rounded-lg border border-gray-200 p-4 bg-gray-50 text-center">
    @php
        $paymentData = $getRecord()->activation_payment_data ?? [];
        $imagePath = $paymentData['proof_path'] ?? null;
    @endphp

    @if($imagePath)
        <div class="mb-2">
            <a href="{{ asset('storage/' . $imagePath) }}" target="_blank">
                <img src="{{ asset('storage/' . $imagePath) }}" 
                     alt="Bukti Transfer" 
                     class="mx-auto max-h-32 rounded shadow-sm border border-gray-300 hover:opacity-90 transition cursor-pointer">
            </a>
        </div>
        <div class="text-xs text-gray-400">
            (Klik gambar untuk memperbesar)
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-4 text-gray-400">
            <x-heroicon-o-photo class="w-10 h-10 mb-2"/>
            <span class="text-xs">Belum ada bukti.</span>
        </div>
    @endif
</div>