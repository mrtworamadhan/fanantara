<!DOCTYPE html>
<html>
<head>
    <title>Bundel Laporan Keuangan - {{ $period->name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .page-break { page-break-after: always; }
        .header { height: 75px;align-items: center; text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 3px; position: relative; }
        .logo { height: 70px; position: absolute; left: 0px; top: 0; margin-left: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { padding: 6px; border: 1px solid #ccc; }
        .bg-gray { background-color: #f5f5f5; font-weight: bold; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        h3 { text-align: center; text-transform: uppercase; margin-top: 20px; }
        .footer-sign { margin-top: 40px; }
        .sign-box { width: 33%; float: left; text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ $logo_path }}" class="logo">
        <div style="font-size: 14px; font-weight: bold; color: crimson;">KOPERASI MULTI PIHAK</div>
        <div style="font-size: 20px; margin-top: 5px; font-weight: bold; color: darkslateblue;">FANANTARA</div>
        <div style="font-size: 12px; font-weight: bold; color: crimson;">KOPERASI MULTI PIHAK</div>
        <div style="font-size: 9px; margin-top: 5px">Gedung Nucira, Lantai 1, Jl. Mr Haryono kav. 27, Tebet Timur - Tebet - Jakarta Selatan</div>
    </div>
    
    <div>Laporan Posisi Keuangan (Neraca)</div>
        <div>Periode: {{ $period->name }}</div>
    <h3>NERACA</h3>
    <table>
        <thead>
            <tr class="bg-gray">
                <th width="70%">URAIAN AKUN</th>
                <th width="30%">SALDO</th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-gray"><td colspan="2">A. AKTIVA (ASET)</td></tr>
            @foreach($neraca['aset_lancar'] as $item)
                <tr><td>{{ $item['code_name'] }}</td><td class="text-right">Rp {{ number_format($item['balance'], 0, ',', '.') }}</td></tr>
            @endforeach
            @foreach($neraca['aset_tetap'] as $item)
                <tr><td>{{ $item['code_name'] }}</td><td class="text-right">Rp {{ number_format($item['balance'], 0, ',', '.') }}</td></tr>
            @endforeach
            <tr class="text-bold bg-gray">
                <td>TOTAL AKTIVA</td>
                <td class="text-right">Rp {{ number_format(collect($neraca['aset_lancar'])->sum('balance') + collect($neraca['aset_tetap'])->sum('balance'), 0, ',', '.') }}</td>
            </tr>

            <tr class="bg-gray"><td colspan="2">B. PASIVA (KEWAJIBAN & EKUITAS)</td></tr>
            @foreach($neraca['hutang'] as $item)
                <tr><td>{{ $item['code_name'] }}</td><td class="text-right">Rp {{ number_format($item['balance'], 0, ',', '.') }}</td></tr>
            @endforeach
            @foreach($neraca['modal'] as $item)
                <tr><td>{{ $item['code_name'] }}</td><td class="text-right">Rp {{ number_format($item['balance'], 0, ',', '.') }}</td></tr>
            @endforeach
            <tr><td>SHU Tahun Berjalan</td><td class="text-right">Rp {{ number_format($neraca['shu_berjalan'], 0, ',', '.') }}</td></tr>
            <tr class="text-bold bg-gray">
                <td>TOTAL PASIVA</td>
                <td class="text-right">Rp {{ number_format(collect($neraca['hutang'])->sum('balance') + collect($neraca['modal'])->sum('balance') + $neraca['shu_berjalan'], 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="page-break"></div>
    <div class="header">
        <img src="{{ $logo_path }}" class="logo">
        <div style="font-size: 14px; font-weight: bold; color: crimson;">KOPERASI MULTI PIHAK</div>
        <div style="font-size: 20px; margin-top: 5px; font-weight: bold; color: darkslateblue;">FANANTARA</div>
        <div style="font-size: 9px; margin-top: 5px">Gedung Nucira, Lantai 1, Jl. Mr Haryono kav. 27, Tebet Timur - Tebet - Jakarta Selatan</div>
        
    </div>

    <h3>PERHITUNGAN HASIL USAHA (PHU)</h3>
    <table>
        <tr class="bg-gray"><td colspan="2">PENDAPATAN</td></tr>
        @foreach($phu['revenue_list'] as $item)
            <tr><td>{{ $item['code_name'] }}</td><td class="text-right">Rp {{ number_format($item['balance'], 0, ',', '.') }}</td></tr>
        @endforeach
        <tr class="text-bold"><td>TOTAL PENDAPATAN</td><td class="text-right">Rp {{ number_format($phu['total_revenue'], 0, ',', '.') }}</td></tr>
        
        <tr class="bg-gray"><td colspan="2">BEBAN-BEBAN</td></tr>
        @foreach($phu['expense_list'] as $item)
            <tr><td>{{ $item['code_name'] }}</td><td class="text-right">Rp {{ number_format($item['balance'], 0, ',', '.') }}</td></tr>
        @endforeach
        <tr class="text-bold"><td>TOTAL BEBAN</td><td class="text-right">Rp {{ number_format($phu['total_expense'], 0, ',', '.') }}</td></tr>
        
        <tr class="bg-gray"><td>SHU BERSIH PERIODE INI</td><td class="text-right">Rp {{ number_format($phu['net_shu'], 0, ',', '.') }}</td></tr>
    </table>

    <div class="page-break"></div>
    <div class="header">
        <img src="{{ $logo_path }}" class="logo">
        <div style="font-size: 14px; font-weight: bold; color: crimson;">KOPERASI MULTI PIHAK</div>
        <div style="font-size: 20px; margin-top: 5px; font-weight: bold; color: darkslateblue;">FANANTARA</div>
        <div style="font-size: 9px; margin-top: 5px">Gedung Nucira, Lantai 1, Jl. Mr Haryono kav. 27, Tebet Timur - Tebet - Jakarta Selatan</div>
        
    </div>
    <h3>LAPORAN PERUBAHAN EKUITAS</h3>
    <p style="text-align: center; margin-top: -10px;">Periode yang berakhir pada {{ $period->end_date }}</p>
    <table>
        <thead>
            <tr class="bg-gray">
                <th>NAMA AKUN</th>
                <th class="text-right">SALDO AWAL</th>
                <th class="text-right">PENAMBAHAN</th>
                <th class="text-right">PENGURANGAN</th>
                <th class="text-right">SALDO AKHIR</th>
            </tr>
        </thead>
        <tbody>
            @foreach($equity_changes as $equity)
                <tr>
                    <td>{{ $equity['account_name'] }}</td>
                    <td class="text-right">{{ number_format($equity['initial_balance'], 0, ',', '.') }}</td>
                    <td class="text-right" style="color: green;">{{ number_format($equity['addition'], 0, ',', '.') }}</td>
                    <td class="text-right" style="color: red;">{{ number_format($equity['reduction'], 0, ',', '.') }}</td>
                    <td class="text-right"><strong>{{ number_format($equity['ending_balance'], 0, ',', '.') }}</strong></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-gray">
                <td>TOTAL EKUITAS</td>
                <td class="text-right">{{ number_format(collect($equity_changes)->sum('initial_balance'), 0, ',', '.') }}</td>
                <td colspan="2"></td>
                <td class="text-right">{{ number_format(collect($equity_changes)->sum('ending_balance'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="page-break"></div>
    <div class="header">
        <img src="{{ $logo_path }}" class="logo">
        <div style="font-size: 14px; font-weight: bold; color: crimson;">KOPERASI MULTI PIHAK</div>
        <div style="font-size: 20px; margin-top: 5px; font-weight: bold; color: darkslateblue;">FANANTARA</div>
        <div style="font-size: 9px; margin-top: 5px">Gedung Nucira, Lantai 1, Jl. Mr Haryono kav. 27, Tebet Timur - Tebet - Jakarta Selatan</div>
        
    </div>
    <h3>LAPORAN ARUS KAS</h3>
    <p style="text-align: center; margin-top: -10px;">Metode Tidak Langsung</p>
    
    <table>
        <tr class="bg-gray"><td colspan="2">ARUS KAS DARI AKTIVITAS OPERASIONAL</td></tr>
        <tr><td>Sisa Hasil Usaha (SHU) Bersih</td><td class="text-right">Rp {{ number_format($arus_kas['operating']['net_income'], 0, ',', '.') }}</td></tr>
        <tr><td>Penyesuaian Modal Kerja (Piutang, Persediaan, Hutang)</td><td class="text-right">Rp {{ number_format($arus_kas['operating']['total'] - $arus_kas['operating']['net_income'], 0, ',', '.') }}</td></tr>
        <tr class="text-bold"><td>Total Arus Kas Operasional</td><td class="text-right">Rp {{ number_format($arus_kas['operating']['total'], 0, ',', '.') }}</td></tr>

        <tr class="bg-gray"><td colspan="2">ARUS KAS DARI AKTIVITAS INVESTASI</td></tr>
        <tr><td>Perolehan/Penjualan Aset Tetap</td><td class="text-right">Rp {{ number_format($arus_kas['investing']['total'], 0, ',', '.') }}</td></tr>
        
        <tr class="bg-gray"><td colspan="2">ARUS KAS DARI AKTIVITAS PENDANAAN</td></tr>
        <tr><td>Penerimaan Simpanan Pokok & Wajib</td><td class="text-right">Rp {{ number_format($arus_kas['financing']['total'], 0, ',', '.') }}</td></tr>

        <tr class="bg-gray text-bold">
            <td>KENAIKAN / (PENURUNAN) KAS BERSIH</td>
            <td class="text-right">Rp {{ number_format($arus_kas['net_increase'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>SALDO KAS AWAL PERIODE</td>
            <td class="text-right">Rp {{ number_format($arus_kas['initial_cash'], 0, ',', '.') }}</td>
        </tr>
        <tr class="bg-gray text-bold" style="font-size: 13px;">
            <td>SALDO KAS AKHIR PERIODE</td>
            <td class="text-right">Rp {{ number_format($arus_kas['final_cash'], 0, ',', '.') }}</td>
        </tr>
    </table>

    <h3>LAMPIRAN: BUKU BESAR (RINGKASAN)</h3>
    @foreach($buku_besar as $akun)
        <table style="margin-top: 10px;">
            <tr class="bg-gray"><td colspan="4">{{ $akun['account_info'] }}</td></tr>
            <tr>
                <td colspan="3">Saldo Awal Periode</td>
                <td class="text-right">Rp {{ number_format($akun['initial_balance'], 0, ',', '.') }}</td>
            </tr>
            <tr class="text-bold">
                <td>Tanggal</td>
                <td>Keterangan</td>
                <td class="text-right">Debit</td>
                <td class="text-right">Kredit</td>
            </tr>
            @foreach($akun['items'] as $it)
                <tr>
                    <td>{{ $it->journalEntry->transaction_date }}</td>
                    <td>{{ $it->journalEntry->description }}</td>
                    <td class="text-right">{{ number_format($it->debit, 0) }}</td>
                    <td class="text-right">{{ number_format($it->credit, 0) }}</td>
                </tr>
            @endforeach
        </table>
    @endforeach

    <div class="page-break"></div>
    <div class="header">
        <img src="{{ $logo_path }}" class="logo">
        <div style="font-size: 14px; font-weight: bold; color: crimson;">KOPERASI MULTI PIHAK</div>
        <div style="font-size: 20px; margin-top: 5px; font-weight: bold; color: darkslateblue;">FANANTARA</div>
        <div style="font-size: 9px; margin-top: 5px">Gedung Nucira, Lantai 1, Jl. Mr Haryono kav. 27, Tebet Timur - Tebet - Jakarta Selatan</div>
        
    </div>

    <h3>LAPORAN PEMBAGIAN SISA HASIL USAHA (SHU)</h3>
    <p style="text-align: center; margin-top: -10px;">Periode Tahun Buku: {{ $period->name }}</p>
    <h4 style="margin-bottom: 5px;">A. RINGKASAN ALOKASI SHU</h4>
    
    <table style="margin-bottom: 30px;">
        <thead>
            <tr class="bg-gray">
                <th width="50%">KATEGORI ALOKASI</th>
                <th width="20%" class="text-right">PERSENTASE</th>
                <th width="30%" class="text-right">NOMINAL</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAlokasi = 0; @endphp
            @if($shu_members && $shu_members->allocation_results)
                @foreach($shu_members->allocation_results as $item)
                    @php 
                        $alokasi = \App\Models\ShuAllocation::find($item['shu_allocation_id']);
                        $nominal = (float) $item['amount'];
                        $totalAlokasi += $nominal;
                    @endphp
                    <tr>
                        <td>{{ $alokasi->name ?? 'Alokasi' }}</td>
                        <td class="text-right">{{ $alokasi->percentage ?? 0 }}%</td>
                        <td class="text-right">Rp {{ number_format($nominal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr class="bg-gray text-bold">
                <td colspan="2">TOTAL SHU YANG DIBAGIKAN</td>
                <td class="text-right">Rp {{ number_format($totalAlokasi, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <h4 style="margin-bottom: 5px;">B. RINCIAN PEMBAGIAN KE ANGGOTA</h4>
    <table>
        <thead>
            <tr class="bg-gray">
                <th width="5%">NO</th>
                <th width="35%">NAMA ANGGOTA</th>
                <th width="20%" class="text-right">JASA MODAL</th>
                <th width="20%" class="text-right">JASA USAHA</th>
                <th width="20%" class="text-right">TOTAL DITERIMA</th>
            </tr>
        </thead>
        <tbody>
            @if($shu_members && $shu_members->details)
                @foreach($shu_members->details as $index => $detail)
                    <tr>
                        <td align="center">{{ $index + 1 }}</td>
                        <td>{{ $detail->member->name }}</td>
                        <td class="text-right">
                            Rp {{ number_format($detail->distribution_breakdown['Jasa Modal'] ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="text-right">
                            Rp {{ number_format($detail->distribution_breakdown['Jasa Usaha'] ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="text-right text-bold">
                            Rp {{ number_format($detail->total_received, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" align="center">Data rincian anggota tidak tersedia.</td>
                </tr>
            @endif
        </tbody>
        @if($shu_members && $shu_members->details)
        <tfoot>
            <tr class="bg-gray text-bold">
                <td colspan="2" align="center">TOTAL JATAH ANGGOTA</td>
                <td class="text-right">Rp {{ number_format($shu_members->details->sum(fn($d) => $d->distribution_breakdown['Jasa Modal'] ?? 0), 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($shu_members->details->sum(fn($d) => $d->distribution_breakdown['Jasa Usaha'] ?? 0), 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($shu_members->details->sum('total_received'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div style="margin-top: 50px;">
        <p class="text-right" style="margin-right: 50px;">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        <div class="footer-sign">
            <div class="sign-box">
                <p>Bendahara</p>
                <br><br><br>
                <p><strong>( {{ $officials['treasurer'] }} )</strong></p>
            </div>
            <div class="sign-box">
                <p>Ketua Koperasi</p>
                <br><br><br>
                <p><strong>( {{ $officials['chairman'] }} )</strong></p>
            </div>
            <div class="sign-box">
                <p>Pengawas</p>
                <br><br><br>
                <p><strong>( {{ $officials['supervisor'] }} )</strong></p>
            </div>
        </div>
    </div>

    <div class="page-break"></div>
    
    <div class="header">
        <img src="{{ $logo_path }}" class="logo">
        <div style="font-size: 14px; font-weight: bold; color: crimson;">KOPERASI MULTI PIHAK</div>
        <div style="font-size: 20px; margin-top: 5px; font-weight: bold; color: darkslateblue;">FANANTARA</div>
        <div style="font-size: 9px; margin-top: 5px">Gedung Nucira, Lantai 1, Jl. Mr Haryono kav. 27, Tebet Timur - Tebet - Jakarta Selatan</div>
        
    </div>
    <h3>BUKU PEMBANTU SIMPANAN ANGGOTA</h3>
    @foreach($member_ledger as $m)
        <div style="margin-top: 20px; border: 1px solid #000; padding: 10px;">
            <strong>Nama Anggota: {{ $m['name'] }}</strong>
            @foreach($m['accounts'] as $acc)
                <table style="margin-top: 5px; font-size: 10px;">
                    <tr class="bg-gray"><td colspan="4">Jenis Simpanan: {{ $acc['account_name'] }}</td></tr>
                    <tr>
                        <td colspan="3">Saldo Awal Periode</td>
                        <td class="text-right">Rp {{ number_format($acc['initial_balance'], 0, ',', '.') }}</td>
                    </tr>
                    @foreach($acc['mutations'] as $mut)
                        <tr>
                            <td>{{ $mut->transaction_date }}</td>
                            <td>{{ $mut->notes }}</td>
                            <td class="text-right">{{ $mut->type === 'deposit' ? '+' : '-' }} {{ number_format($mut->amount, 0) }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    <tr class="text-bold">
                        <td colspan="3">Saldo Akhir</td>
                        <td class="text-right">Rp {{ number_format($acc['final_balance'], 0, ',', '.') }}</td>
                    </tr>
                </table>
            @endforeach
        </div>
    @endforeach

</body>
</html>