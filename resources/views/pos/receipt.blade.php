<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $order->order_number }}</title>
    <style>
        /* RESET CSS UNTUK PRINTER */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Courier New', Courier, monospace; /* Font struk klasik */
        }
        
        body {
            width: 58mm; /* Standar printer thermal kecil, ubah ke 80mm jika perlu */
            padding: 2mm;
            font-size: 12px;
            color: #000;
        }

        /* UTILITY CLASS */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }

        /* GARIS PEMBATAS (DASHED) */
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        /* TABEL ITEM */
        .items { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        .items th, .items td { padding: 2px 0; vertical-align: top; }
        
        /* HIDE SAAT DI LAYAR PC BIASA (Opsional) */
        @media screen {
            body { 
                background: #eee; 
                margin: 0 auto; /* Tengahin pas preview */
                margin-top: 20px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                background: #fff;
            }
        }

        /* PRINT SETTINGS */
        @media print {
            @page { margin: 0; size: auto; }
            body { margin: 0; box-shadow: none; }
        }
    </style>
</head>
<body>
    
    <div class="text-center mb-2">
        <h3 class="bold" style="font-size: 14px;">KOPERASI FANANTARA</h3>
        <p>Jl. Fanantara No. 1, Jakarta</p>
        <p>Telp: 021-12345678</p>
    </div>

    <div class="divider"></div>

    <div class="mb-1">
        <p>No: {{ $order->order_number }}</p>
        <p>Tgl: {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p>Kasir: {{ $order->payments->first()->creator->name ?? 'Admin' }}</p>
        <p>Plgn: {{ $order->member->name ?? 'Umum' }}</p>
    </div>

    <div class="divider"></div>

    <table class="items">
        @foreach($order->items as $item)
        <tr>
            <td colspan="3" class="bold">{{ $item->product->name }}</td>
        </tr>
        <tr>
            <td width="30%">{{ $item->quantity }} x</td>
            <td width="30%" class="text-right">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
            <td width="40%" class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <table class="items">
        <tr>
            <td class="bold">TOTAL</td>
            <td class="text-right bold" style="font-size: 14px;">{{ number_format($order->total_amount, 0, ',', '.') }}</td>
        </tr>
        
        @if($order->payment_status === 'paid')
        <tr>
            <td>Bayar (Tunai)</td>
            <td class="text-right">{{ number_format($order->payments->sum('amount'), 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td class="text-right">0</td> 
        </tr>
        @else
        <tr>
            <td colspan="2" class="text-center bold" style="margin-top: 5px;">* TEMPO / HUTANG *</td>
        </tr>
        @endif
    </table>

    <div class="divider"></div>

    <div class="text-center" style="margin-top: 10px;">
        <p>Terima Kasih</p>
        <p>Selamat Belanja Kembali</p>
        <br>
        <p style="font-size: 10px;">Powered by Fanantara ERP</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
            // Opsional: Tutup window otomatis setelah print (tapi kadang diblokir browser)
            // setTimeout(function(){ window.close(); }, 500); 
        }
    </script>
</body>
</html>