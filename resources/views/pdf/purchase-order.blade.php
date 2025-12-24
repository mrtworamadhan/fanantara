<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order #{{ $purchase->purchase_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #333; padding: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $settings['name'] }}</h1>
        <h3>PURCHASE ORDER (PO)</h3>
        <p>No: {{ $purchase->purchase_number }} | Tgl: {{ $purchase->created_at->format('d/m/Y') }}</p>
    </div>

    <div style="margin-bottom: 20px; border: 1px solid #333; padding: 10px;">
        <strong>Mohon dikirimkan barang-barang berikut ke alamat:</strong><br>
        {{ $settings['address'] }}
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th style="text-align: center;">Qty Order</th>
                <th style="text-align: right;">Estimasi Harga</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchase->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->unit_cost, 0) }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->unit_cost * $item->quantity, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold;">TOTAL ORDER</td>
                <td style="text-align: right; font-weight: bold;">Rp {{ number_format($purchase->total_amount, 0) }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 40px;">
        <p>Catatan: Mohon sertakan Invoice saat pengiriman barang.</p>
        <br>
        <p>Hormat Kami,</p>
        <br><br><br>
        <p>Procurement {{ $settings['name'] }}</p>
    </div>
</body>
</html>