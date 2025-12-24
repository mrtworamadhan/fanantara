<!DOCTYPE html>
<html>
<head>
    <title>Rekening Koran Anggota</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px; vertical-align: top; }
        .label { font-weight: bold; width: 130px; }

        .trx-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .trx-table th, .trx-table td { border: 1px solid #000; padding: 6px; text-align: left; }
        .trx-table th { background-color: #f0f0f0; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .balance-row { background-color: #eee; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h1>KOPERASI FANANTARA</h1>
        <p>Jalan Supply Chain No. 1, Jakarta</p>
        <p>Telp: 021-12345678 | Email: admin@fanantara.id</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Anggota</td>
            <td>: {{ $account->member->name }}</td>
            <td class="label">Tanggal Cetak</td>
            <td>: {{ now()->format('d F Y H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Nomor Anggota</td>
            <td>: {{ $account->member->member_number ?? '-' }}</td>
            <td class="label">Jenis Simpanan</td>
            <td>: {{ $account->savingType->name }}</td>
        </tr>
        <tr>
            <td class="label">Nomor Rekening</td>
            <td>: <strong>{{ $account->account_number }}</strong></td>
            <td class="label">Saldo Akhir</td>
            <td>: <strong>Rp {{ number_format($account->balance, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <h3 style="margin-bottom: 5px;">Mutasi Transaksi</h3>

    <table class="trx-table">
        <thead>
            <tr>
                <th width="15%">Tanggal</th>
                <th width="10%">Tipe</th>
                <th>Keterangan</th>
                <th width="15%" class="text-right">Debit (Masuk)</th>
                <th width="15%" class="text-right">Kredit (Keluar)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $trx)
            <tr>
                <td>{{ \Carbon\Carbon::parse($trx->transaction_date)->format('d/m/Y') }}</td>
                <td class="text-center">
                    {{ $trx->type == 'deposit' ? 'STR' : 'TRK' }}
                </td>
                <td>{{ $trx->notes ?? '-' }}</td>
                <td class="text-right">
                    @if($trx->type == 'deposit')
                        Rp {{ number_format($trx->amount, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-right">
                    @if($trx->type == 'withdrawal')
                        Rp {{ number_format($trx->amount, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada transaksi pada rekening ini.</td>
            </tr>
            @endforelse
            
            <tr class="balance-row">
                <td colspan="3" class="text-right">Total Saldo Saat Ini</td>
                <td colspan="2" class="text-right">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <p>Jakarta, {{ now()->format('d F Y') }}</p>
        <br><br><br>
        <p>( Teller / Admin )</p>
    </div>

</body>
</html>