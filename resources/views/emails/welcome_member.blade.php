<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Fanantara</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #334155; margin: 0; padding: 0; background-color: #f8fafc; }
        .wrapper { width: 100%; padding: 40px 0; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 24px; overflow: hidden; shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; }
        .header { background: linear-gradient(135deg, #581c87 0%, #a855f7 100%); padding: 40px 20px; text-align: center; color: white; }
        .logo { width: 80px; height: 80px; margin-bottom: 20px; }
        .content { padding: 40px; }
        .welcome-text { font-size: 24px; font-weight: 800; color: #581c87; margin-bottom: 10px; }
        .member-card { background: #f0fdf4; border: 1px border-dashed #bbf7d0; padding: 20px; border-radius: 16px; margin: 25px 0; }
        .member-info { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 13px; }
        .label { color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
        .value { color: #581c87; font-weight: 800; }
        
        .table-title { font-size: 14px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-top: 30px; margin-bottom: 15px; display: block; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { text-align: left; font-size: 12px; color: #94a3b8; padding: 10px 0; border-bottom: 2px solid #f1f5f9; }
        td { padding: 15px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .acc-number { font-family: 'Courier New', Courier, monospace; font-weight: 700; color: #0f172a; background: #f1f5f9; padding: 4px 8px; border-radius: 4px; }
        
        .cta-box { text-align: center; margin-top: 20px; }
        .button { display: inline-block; padding: 16px 32px; background-color: #a855f7; color: #ffffff !important; text-decoration: none; border-radius: 12px; font-weight: 700; box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.2); }
        .footer { padding: 30px; text-align: center; font-size: 12px; color: #94a3b8; background: #f8fafc; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <img src="{{ $message->embed(public_path('images/logoElemen.png')) }}" alt="Logo Fanantara" class="logo">
                <div style="font-size: 10px; font-weight: 700; letter-spacing: 4px; text-transform: uppercase; opacity: 0.8;">Koperasi Multi Pihak</div>
                <div style="font-size: 22px; font-weight: 900; letter-spacing: 1px;">FANANTARA</div>
            </div>

            <div class="content">
                <h2 class="welcome-text">Halo, {{ $member->user->name ?? $member->name }}!</h2>
                <p>Selamat! Pendaftaran Anda telah disetujui. Kini Anda resmi menjadi bagian dari ekosistem ekonomi masa depan <strong>Koperasi Fanantara</strong>.</p>

                <div class="member-card">
                    <div class="member-info">
                        <span class="label">ID Anggota</span>
                        <span class="value">{{ $member->member_number }}</span>
                    </div>
                    <div class="member-info">
                        <span class="label">Status</span>
                        <span class="value" style="color: #059669;">● AKTIF</span>
                    </div>
                </div>

                <span class="table-title">Rekening Simpanan Anda</span>
                <table>
                    <thead>
                        <tr>
                            <th>JENIS SIMPANAN</th>
                            <th>NOMOR REKENING</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($member->savingAccounts as $acc)
                        <tr>
                            <td style="font-weight: 600;">{{ $acc->savingType->name }}</td>
                            <td><span class="acc-number">{{ $acc->account_number }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <p style="font-size: 13px; color: #64748b; font-style: italic;">
                    *Gunakan nomor rekening di atas untuk melakukan setoran simpanan melalui transfer bank atau melalui kasir koperasi.
                </p>

                <div class="cta-box">
                    <a href="{{ config('app.url') . '/dashboard' }}" class="button">Masuk ke Dashboard PWA</a>
                </div>

                <p style="margin-top: 40px;">
                    Terlampir kami sertakan <strong>Kartu Tanda Anggota (KTA) Digital</strong> Anda. Silakan simpan file tersebut sebagai identitas resmi keanggotaan.
                </p>
            </div>

            <div class="footer">
                <p><strong>Koperasi Multi Pihak Fanantara</strong><br>
                Supply Chain Integration Ecosystem<br>
                Wisma Aria Lt. 3 Suites 301<br>
                Jl. Cokroaminoto no 81, Menteng, Jakarata Pusat.</p>
                <p style="margin-top: 20px;">Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
            </div>
        </div>
    </div>
</body>
</html>