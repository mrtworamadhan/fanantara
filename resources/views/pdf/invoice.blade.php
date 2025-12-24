<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #2d3748; }
        .details { width: 100%; margin-bottom: 20px; }
        .details td { vertical-align: top; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .total { text-align: right; font-weight: bold; margin-top: 20px; }
        .status { 
            padding: 5px 10px; color: white; border-radius: 5px; font-weight: bold;
            background-color: {{ $order->payment_status == 'paid' ? 'green' : ($order->payment_status == 'partial' ? 'orange' : 'red') }};
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $settings['name'] }}</h1>
        <p>{{ $settings['address'] }} | {{ $settings['phone'] }}</p>
        <hr>
        <h2>INVOICE / NOTA PENJUALAN</h2>
    </div>

    <table class="details">
        <tr>
            <td width="60%">
                <strong>Kepada Yth:</strong><br>
                {{ $order->member->name }}<br>
                {{ $order->member->address ?? '-' }}<br>
                {{ $order->member->phone ?? '-' }}
            </td>
            <td width="40%" style="text-align: right;">
                <strong>No. Order:</strong> {{ $order->order_number }}<br>
                <strong>Tanggal:</strong> {{ $order->created_at->format('d M Y') }}<br>
                <strong>Status Bayar:</strong> <span class="status">{{ strtoupper($order->payment_status) }}</span>
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Harga</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->unit_price, 0) }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->unit_price * $item->quantity, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold;">TOTAL TAGIHAN</td>
                <td style="text-align: right; font-weight: bold;">Rp {{ number_format($order->total_amount, 0) }}</td>
            </tr>
            @if($order->payment_status !== 'unpaid')
            <tr>
                <td colspan="3" style="text-align: right;">Sudah Dibayar</td>
                <td style="text-align: right;">Rp {{ number_format($order->payments->sum('amount'), 0) }}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right; color: red;">Sisa Tagihan</td>
                <td style="text-align: right; color: red;">Rp {{ number_format($order->remaining_balance, 0) }}</td>
            </tr>
            @endif
        </tfoot>
    </table>

    <div style="margin-top: 50px;">
        <table width="100%">
            <tr>
                <td align="center">Hormat Kami,<br><br><br><br>( Admin Koperasi )</td>
                <td align="center">Penerima,<br><br><br><br>( {{ $order->member->name }} )</td>
            </tr>
        </table>
    </div>
</body>
</html>