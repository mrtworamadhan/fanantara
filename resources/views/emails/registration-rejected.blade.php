<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembaruan Status Pendaftaran - Fanantara</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #334155; margin: 0; padding: 0; background-color: #f8fafc; }
        .wrapper { width: 100%; padding: 40px 0; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 24px; overflow: hidden; border: 1px solid #e2e8f0; }
        .header { background: linear-gradient(135deg, #991b1b 0%, #dc2626 100%); padding: 40px 20px; text-align: center; color: white; }
        .logo { width: 80px; height: 80px; margin-bottom: 20px; }
        .content { padding: 40px; }
        .status-badge { display: inline-block; padding: 6px 12px; background: #fef2f2; border: 1px solid #fee2e2; color: #dc2626; border-radius: 8px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; }
        .welcome-text { font-size: 24px; font-weight: 800; color: #991b1b; margin-bottom: 10px; }
        
        .rejection-box { background: #fff1f2; border-left: 4px solid #f43f5e; padding: 25px; border-radius: 0 16px 16px 0; margin: 25px 0; }
        .rejection-label { font-size: 12px; font-weight: 700; color: #be123c; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px; }
        .rejection-text { color: #881337; font-size: 15px; font-style: italic; font-weight: 500; line-height: 1.5; }
        
        .cta-box { text-align: center; margin-top: 30px; }
        .button { display: inline-block; padding: 16px 32px; background-color: #059669; color: #ffffff !important; text-decoration: none; border-radius: 12px; font-weight: 700; box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.2); }
        .footer { padding: 30px; text-align: center; font-size: 12px; color: #94a3b8; background: #f8fafc; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            {{-- HEADER DENGAN LOGO --}}
            <div class="header">
                <img src="{{ $message->embed(public_path('images/logoElemen.png')) }}" alt="Logo Fanantara" class="logo">
                <div style="font-size: 10px; font-weight: 700; letter-spacing: 4px; text-transform: uppercase; opacity: 0.8;">Pembaruan Status</div>
                <div style="font-size: 22px; font-weight: 900; letter-spacing: 1px;">PENDAFTARAN</div>
            </div>

            <div class="content">
                <div class="status-badge">Ditangguhkan / Rejected</div>
                <h2 class="welcome-text">Halo, {{ $member->name }}</h2>
                <p>Terima kasih telah melakukan pendaftaran di Koperasi Fanantara. Namun, setelah tim kami melakukan verifikasi terhadap data dan bukti pembayaran Anda, saat ini kami <strong>belum dapat menyetujui</strong> pendaftaran Anda.</p>

                <div class="rejection-box">
                    <span class="rejection-label">Alasan Penolakan:</span>
                    <p class="rejection-text">"{{ $rejectionNote }}"</p>
                </div>

                <p>Jangan khawatir, Anda tetap memiliki kesempatan untuk bergabung. Silakan lakukan perbaikan data atau unggah ulang bukti pembayaran yang valid melalui tombol di bawah ini:</p>

                <div class="cta-box">
                    <a href="{{ route('member.activation') }}" class="button">Perbaiki Pendaftaran</a>
                </div>

                <p style="margin-top: 40px; font-size: 13px; color: #64748b;">
                    Jika Anda merasa ada kesalahan atau butuh bantuan teknis, Anda dapat membalas email ini atau menghubungi Customer Service kami melalui layanan WhatsApp resmi koperasi.
                </p>
            </div>

            <div class="footer">
                <p><strong>Koperasi Multi Pihak Fanantara</strong><br>
                Supply Chain Integration Ecosystem<br>
                &copy; {{ date('Y') }} Hak Cipta Dilindungi</p>
            </div>
        </div>
    </div>
</body>
</html>