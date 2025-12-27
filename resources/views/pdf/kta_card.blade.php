<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KTA Digital - {{ $member->user->name }}</title>
    <style>
        /* Reset & Base */
        @page { margin: 0; size: 85.6mm 53.98mm; } /* Ukuran Kartu Kredit Standar */
        body { margin: 0; padding: 0; font-family: 'Helvetica', 'Arial', sans-serif; }

        /* Container Utama dengan Gradasi */
        .card-container {
            width: 85.6mm;
            height: 53.98mm;
            position: relative;
            background-image:; 
        }

        

        /* HEADER */
        .header {
            position: absolute;
            top: 4mm;
            left: 4mm;
            right: 4mm;
            z-index: 10;
        }
        
        /* Table layout untuk menggantikan Flexbox di PDF yang kadang bug */
        .header-table { width: 100%; border-collapse: collapse; }
        .header-left { width: 70%; vertical-align: top; }
        .header-right { width: 30%; text-align: right; vertical-align: top; }

        .logo-text h3 {
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
            color: #d1fae5; /* emerald-100 */
        }
        .logo-text h2 {
            font-size: 10pt;
            font-weight: 900;
            text-transform: uppercase;
            margin: 0;
            line-height: 1;
            color: #ffffff;
        }

        .badge {
            display: inline-block;
            background-color: #fbbf24; /* amber-400 */
            color: #064e3b; /* emerald-900 */
            font-size: 6pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 2px 5px;
            border-radius: 3px;
            letter-spacing: 0.5px;
        }

        /* CONTENT BAWAH */
        .content-table {
            position: absolute;
            bottom: 4mm;
            left: 4mm;
            width: 92%; /* Agar tidak terlalu mepet kanan */
            z-index: 10;
            border-collapse: collapse;
        }
        .info-col { width: 75%; vertical-align: bottom; padding-right: 2mm; }
        .qr-col { width: 25%; vertical-align: bottom; text-align: right; }

        .label {
            font-size: 6pt;
            color: #a7f3d0; /* emerald-200 */
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1px;
        }

        .member-name {
            font-size: 11pt;
            font-weight: bold;
            color: #fcd34d; /* amber-300 */
            text-transform: uppercase;
            margin-bottom: 3mm;
            line-height: 1.1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Rincian ID & Tanggal */
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 2mm; }
        .details-col { vertical-align: top; }
        .details-value {
            font-size: 8pt;
            font-weight: bold;
            margin: 0;
            font-family: 'Courier', monospace; /* Monospace font */
        }

        /* Alamat */
        .address-box {
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 1mm;
        }
        .address-text {
            font-size: 6pt;
            color: #d1fae5;
            opacity: 0.9;
            line-height: 1.2;
            margin: 0;
        }

        /* QR Code Box */
        .qr-container {
            background: white;
            padding: 1mm;
            border-radius: 2mm;
            width: 16mm;
            height: 16mm;
            float: right; /* Agar nempel kanan */
        }
        .qr-img { width: 100%; height: 100%; }

    </style>
</head>
<body>

    <div class="card-container"
     style="
        background-image: url('');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
     ">
        <!-- {{-- Pastikan path gambar absolute path untuk PDF (public_path) --}}
        <img src="{{ public_path('images/logoElemen.png') }}" class="watermark">
        <div class="blur-circle"></div>

        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="header-left">
                        <div class="logo-text">
                            <h3>Koperasi</h3>
                            <h2>Fanantara</h2>
                        </div>
                    </td>
                    <td class="header-right">
                        <span class="badge">Member</span>
                    </td>
                </tr>
            </table>
        </div> -->

        <table class="content-table">
            <tr>
                <td class="info-col">
                    <div class="label">Nama Anggota</div>
                    <div class="member-name">{{ Str::limit($member->user->name ?? $member->name, 25) }}</div>

                    <table class="details-table">
                        <tr>
                            <td class="details-col" width="50%">
                                <div class="label">ID Anggota</div>
                                <p class="details-value">{{ $member->member_number }}</p>
                            </td>
                            <td class="details-col" width="50%">
                                <div class="label">Bergabung</div>
                                <p class="details-value">{{ $member->created_at->format('M Y') }}</p>
                            </td>
                        </tr>
                    </table>

                    <div class="address-box">
                        @php
                            $desa = $member->village_code ? \App\Models\Wilayah::where('kode', $member->village_code)->value('nama') : '';
                            $kota = $member->city_code ? \App\Models\Wilayah::where('kode', $member->city_code)->value('nama') : '';
                            
                            $addrParts = [];
                            if($member->street_address) $addrParts[] = Str::limit($member->street_address, 20);
                            if($desa) $addrParts[] = Str::title($desa);
                            if($kota) $addrParts[] = Str::title($kota);
                            
                            $fullAddr = implode(', ', $addrParts);
                        @endphp
                        <p class="address-text">{{ $fullAddr ?: 'Alamat belum dilengkapi' }}</p>
                    </div>
                </td>

                <td class="qr-col">
                    <div class="qr-container">
                        {{-- Gunakan base64 untuk QR Code agar aman di PDF --}}
                        <img src="data:image/png;base64,{{ base64_encode(SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(100)->margin(0)->generate($member->member_number)) }}" class="qr-img">
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>