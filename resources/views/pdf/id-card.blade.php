@php use SimpleSoftwareIO\QrCode\Facades\QrCode; @endphp
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

            background-color: #f0f0f0;

        }
        
        /* Container Kartu */
        .card {
            width: 85.6mm;

            height: 53.98mm;

            position: relative;

            
            border-radius: 4mm;

            overflow: hidden;

            box-shadow: 0 4px 6px rgba(0,0,0,0.3);

            margin: 20px auto;
        }

        .bg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
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
            height: 8mm; 
            flex-shrink: 0;
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
            background-color: #fbbf24;
            color: #064e3b;
            font-size: 6px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 1px 4px;
            border-radius: 2px;
            letter-spacing: 0.5px;
        }

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
            color: #a7f3d0;
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

    </style>
</head>
<body>

    <div class="card">
        <img src="{{ public_path('images/bg-kta.png') }}" class="bg">
        <!-- <div class="circle-blur"></div>
        <div class="header">
            
            <div class="logo-section">
                <img src="{{ asset('images/logo3d.png') }}" class="logo-img">
                <div class="logo-text">
                    <h3>Koperasi Multipihak</h3>
                    <h2>FANANTARA</h2>
                    <h2>Formas Anugerah Nusantara</h2>
                </div>
            </div>
            <div class="badge">Member</div>
        </div> -->

        <div class="content">
            <div class="info-left">
                <div class="label">Nama Anggota</div>
                <div class="name">{{ Str::title($member->name) }}</div>

                <div class="details-row">
                    <div class="detail-item">
                        <div class="label">ID Anggota</div>
                        <p class="'number">{{ $member->member_number }}</p>
                    </div>
                    <div class="detail-item">
                        <div class="label">Bergabung</div>
                        <p class="'number">{{ $member->created_at->format('M Y') }}</p>
                    </div>
                </div>

                <div class="address-box">
                    @php
                        // Logic Alamat Singkat (Inline PHP biar ringkas di view)
                        $desa = $member->village_code ? \App\Models\Wilayah::where('kode', $member->village_code)->value('nama') : '';
                        $kota = $member->city_code ? \App\Models\Wilayah::where('kode', $member->city_code)->value('nama') : '';
                        
                        $addrParts = [];
                        if($member->street_address) $addrParts[] = Str::title($member->street_address);
                        if($desa) $addrParts[] = Str::title($desa);
                        if($kota) $addrParts[] = Str::title($kota);
                        
                        $fullAddr = implode(', ', $addrParts);
                    @endphp
                    <p class="address">{{ $fullAddr ?: 'Alamat belum lengkap' }}</p>
                </div>
            </div>

            <div class="qr-box">
                {!! QrCode::size(100)->margin(0)->generate($member->member_number) !!}
            </div>
        </div>
    </div>

    <script>
        window.onload = function() { window.print(); }
    </script>
</body>
</html>