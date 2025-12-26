<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KTA - {{ $member->user->name }}</title>
    <style>
        
        body {

            margin: 0;

            padding: 0;

            font-family: 'Arial', sans-serif;

            background-color: #f0f0f0; /* Background luar (buat preview) */

        }
        
        /* Container Kartu */
        .card {
             width: 85.6mm;

            height: 53.98mm;

            position: relative;

            background: linear-gradient(135deg, #059669 0%, #115e59 100%);
            color: white;

            border-radius: 4mm;

            overflow: hidden;

            box-shadow: 0 4px 6px rgba(0,0,0,0.3);

            margin: 20px auto; /* Tengahin buat preview */
        }

        /* Hiasan Background */
        .watermark {
            position: absolute;
            bottom: -15mm;
            right: -10mm;
            width: 50mm;
            height: 50mm;
            opacity: 0.1;
            transform: rotate(12deg);
            z-index: 0;
        }
        .circle-blur {
            position: absolute;
            top: -10mm;
            right: -10mm;
            width: 40mm;
            height: 40mm;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: 0;
        }

        /* Header (Logo & Label) */
        .header {
            position: absolute;
            top: 4mm;
            left: 4mm;
            right: 4mm;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            z-index: 10;
        }
        .logo-section {
            display: flex;
            align-items: center;
            gap: 2mm;
        }
        .logo-img {
            width: 8mm;
            height: 8mm;
            object-fit: contain;
        }
        .logo-text h3 {
            font-size: 7px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
            color: #d1fae5; /* emerald-100 */
        }
        .logo-text h2 {
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            margin: 0;
            line-height: 1;
        }
        .badge {
            background-color: #fbbf24; /* amber-400 */
            color: #064e3b; /* emerald-900 */
            font-size: 6px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 1px 4px;
            border-radius: 2px;
            letter-spacing: 0.5px;
        }

        /* Content Utama (Bawah) */
        .content {
            position: absolute;
            bottom: 4mm;
            left: 4mm;
            right: 4mm;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            z-index: 10;
        }

        .info-left {
            flex: 1;
            padding-right: 2mm;
        }

        .label {
            font-size: 6px;
            color: #a7f3d0; /* emerald-200 */
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1px;
        }

        .name {
            font-size: 11px;
            font-weight: bold;
            color: #fcd34d; /* amber-300 */
            text-transform: uppercase;
            margin-bottom: 2mm;
            line-height: 1.1;
        }

        .details-row {
            display: flex;
            gap: 3mm;
            margin-bottom: 2mm;
        }
        
        .detail-item p {
            font-size: 8px;
            font-weight: bold;
            margin: 0;
            font-family: 'Courier New', monospace;
        }

        .address-box {
            border-top: 0.5px solid rgba(255,255,255,0.2);
            padding-top: 1mm;
        }
        .address {
            font-size: 6px;
            color: #d1fae5;
            opacity: 0.9;
            line-height: 1.2;
            margin: 0;
        }

        .qr-box {
            background: white;
            padding: 1mm;
            border-radius: 2mm;
            width: 16mm;
            height: 16mm;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @media print {

            body { background: none; margin: 0; }

            .card-container {

                margin: 0;

                box-shadow: none;

                /* Force Print Background Colors */

                -webkit-print-color-adjust: exact;

                print-color-adjust: exact;

            }

        }
    </style>
</head>
<body>

    <div class="card">
        <img src="{{ asset('images/logoElemen.png') }}" class="watermark">
        <div class="circle-blur"></div>
        <div class="header">
            <div class="logo-section">
                <div class="logo-text">
                    <h3>Koperasi</h3>
                    <h2>Fanantara</h2>
                </div>
            </div>
            <div class="badge">Member</div>
        </div>

        <div class="content">
            <div class="info-left">
                <div class="label">Nama Anggota</div>
                <div class="name">{{ Str::limit($member->user->name, 25) }}</div>

                <div class="details-row">
                    <div class="detail-item">
                        <div class="label">ID Anggota</div>
                        <p>{{ $member->member_number }}</p>
                    </div>
                    <div class="detail-item">
                        <div class="label">Bergabung</div>
                        <p>{{ $member->created_at->format('M Y') }}</p>
                    </div>
                </div>

                <div class="address-box">
                    @php
                        // Logic Alamat Singkat (Inline PHP biar ringkas di view)
                        $desa = $member->village_code ? \App\Models\Wilayah::where('kode', $member->village_code)->value('nama') : '';
                        $kota = $member->city_code ? \App\Models\Wilayah::where('kode', $member->city_code)->value('nama') : '';
                        
                        $addrParts = [];
                        if($member->street_address) $addrParts[] = Str::limit($member->street_address, 20);
                        if($desa) $addrParts[] = Str::title($desa);
                        if($kota) $addrParts[] = Str::title($kota);
                        
                        $fullAddr = implode(', ', $addrParts);
                    @endphp
                    <p class="address">{{ $fullAddr ?: 'Alamat belum lengkap' }}</p>
                </div>
            </div>

            <div class="qr-box">
                <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(100)->margin(0)->generate($member->member_number)) }}" 
                     style="width: 100%; height: 100%;">
            </div>
        </div>
    </div>

    <script>
        window.onload = function() { window.print(); }
    </script>
</body>
</html>