<!DOCTYPE html>
<html>
<head><title>Selamat Datang</title></head>
<body>
    <h2>Halo, {{ $member->user->name ?? $member->name }}!</h2>
    <p>Selamat bergabung menjadi anggota resmi Koperasi Konsumen Syariah.</p>
    
    <p>Berikut adalah data keanggotaan Anda:</p>
    <ul>
        <li><strong>No. Anggota:</strong> {{ $member->member_number }}</li>
        <li><strong>Status:</strong> Aktif</li>
    </ul>

    <h3>Rekening Simpanan Anda (Virtual Account)</h3>
    <p>Gunakan nomor ini untuk melakukan penyetoran:</p>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr style="background: #f3f3f3;"><th>Jenis Simpanan</th><th>Nomor Rekening</th></tr>
        @foreach($accounts as $acc)
        <tr>
            <td>{{ $acc->savingType->name }} ({{ $acc->savingType->code }})</td>
            <td><strong>{{ $acc->account_number }}</strong></td>
        </tr>
        @endforeach
    </table>

    <p>Terlampir dalam email ini adalah <strong>Kartu Tanda Anggota (KTA) Digital</strong> Anda. Silakan disimpan.</p>
    
    <p>Salam Hangat,<br>Pengurus Koperasi</p>
</body>
</html>