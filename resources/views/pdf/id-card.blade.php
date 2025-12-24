<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ID Card - {{ $member->name }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0; /* Background luar (buat preview) */
        }

        .card-container {
            width: 85.6mm;
            height: 53.98mm;
            position: relative;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); /* Warna Khas Koperasi (Biru) */
            color: white;
            border-radius: 4mm;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
            margin: 20px auto; /* Tengahin buat preview */
        }

        /* Desain Background Abstrak (Hiasan) */
        .circle-bg {
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -50px;
            right: -50px;
        }

        .content {
            padding: 5mm;
            display: flex;
            align-items: center;
            height: 100%;
        }

        .photo-area {
            width: 25mm;
            text-align: center;
            z-index: 2;
        }

        .photo {
            width: 22mm;
            height: 22mm;
            border-radius: 50%;
            border: 2px solid white;
            object-fit: cover;
            background-color: #ddd;
        }

        .info-area {
            flex: 1;
            padding-left: 4mm;
            z-index: 2;
        }

        .company-name {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
            margin-bottom: 2px;
        }

        .member-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2px;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .member-type {
            font-size: 8px;
            background: rgba(255,255,255,0.2);
            padding: 1px 4px;
            border-radius: 2px;
            display: inline-block;
            margin-bottom: 5px;
        }

        .member-id {
            font-size: 10px;
            font-family: 'Courier New', monospace;
        }

        .qr-area {
            position: absolute;
            bottom: 4mm;
            right: 4mm;
            background: white;
            padding: 2px;
            border-radius: 2px;
        }

        /* PRINT SETTINGS */
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

    <div class="card-container">
        <div class="circle-bg"></div>

        <div class="content">
            <div class="photo-area">
                @if($member->image_url)
                    <img src="{{ asset('storage/' . $member->image_url) }}" class="photo">
                @else
                    <div class="photo" style="display:flex; align-items:center; justify-content:center; color:#555; font-size:20px;">
                        👤
                    </div>
                @endif
            </div>

            <div class="info-area">
                <div class="company-name">KOPERASI FANANTARA</div>
                <div class="member-name">{{ Str::limit($member->name, 20) }}</div>
                <div class="member-type">{{ strtoupper($member->type) }}</div>
                <div class="member-id">ID: {{ str_pad($member->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        <div class="qr-area">
            {!! QrCode::size(50)->generate($member->id) !!}
        </div>
    </div>

    <script>
        // Auto Print saat dibuka
        window.onload = function() { window.print(); }
    </script>
</body>
</html>