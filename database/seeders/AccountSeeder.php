<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            // --- 1. ASET (ASSETS) ---
            // 1.1 Aset Lancar
            ['code' => '1101', 'name' => 'Kas di Koperasi', 'type' => 'asset'],
            ['code' => '1102', 'name' => 'Kas di Bank', 'type' => 'asset'],
            ['code' => '1103', 'name' => 'Piutang Usaha Anggota', 'type' => 'asset'], // [cite: 388]
            ['code' => '1104', 'name' => 'Piutang Usaha Non-Anggota', 'type' => 'asset'], // [cite: 389]
            ['code' => '1105', 'name' => 'Piutang Simpanan (Anggota)', 'type' => 'asset'],
            ['code' => '1106', 'name' => 'Persediaan Barang Dagang', 'type' => 'asset'], // [cite: 391]
            ['code' => '1107', 'name' => 'Biaya Dibayar Dimuka', 'type' => 'asset'],
            
            // 1.2 Aset Tidak Lancar
            ['code' => '1201', 'name' => 'Aset Tetap - Peralatan', 'type' => 'asset'],
            ['code' => '1202', 'name' => 'Akumulasi Penyusutan Peralatan', 'type' => 'asset'], // Gunakan minus saat reporting

            // --- 2. LIABILITAS (LIABILITIES) ---
            // 2.1 Jangka Pendek
            ['code' => '2101', 'name' => 'Hutang Usaha', 'type' => 'liability'], // Ke Supplier
            ['code' => '2102', 'name' => 'Simpanan Sukarela (Jangka Pendek)', 'type' => 'liability'], // [cite: 403]
            ['code' => '2103', 'name' => 'SHU Belum Dibagikan', 'type' => 'liability'],
            ['code' => '2104', 'name' => 'Beban Masih Harus Dibayar', 'type' => 'liability'],
            
            // 2.2 Jangka Panjang
            ['code' => '2201', 'name' => 'Simpanan Berjangka (Anggota)', 'type' => 'liability'], // [cite: 407]

            // --- 3. EKUITAS (EQUITY) ---
            // 3.1 Modal Anggota (HANYA POKOK & WAJIB)
            ['code' => '3101', 'name' => 'Simpanan Pokok', 'type' => 'equity'], // [cite: 411]
            ['code' => '3102', 'name' => 'Simpanan Wajib', 'type' => 'equity'], // [cite: 412]
            
            // 3.2 Cadangan & SHU
            ['code' => '3201', 'name' => 'Cadangan SHU', 'type' => 'equity'],
            ['code' => '3301', 'name' => 'SHU Tahun Berjalan', 'type' => 'equity'], // [cite: 417]

            // --- 4. PENDAPATAN (REVENUE) ---
            // 4.1 Pendapatan Anggota
            ['code' => '4101', 'name' => 'Pendapatan Jasa/Penjualan (Anggota)', 'type' => 'revenue'], // [cite: 421]
            ['code' => '4102', 'name' => 'Pendapatan Toko (Anggota)', 'type' => 'revenue'],
            
            // 4.2 Pendapatan Non-Anggota
            ['code' => '4201', 'name' => 'Pendapatan Jasa/Penjualan (Non-Anggota)', 'type' => 'revenue'], // [cite: 424]

            // --- 5. BEBAN (EXPENSES) ---
            ['code' => '5100', 'name' => 'Harga Pokok Penjualan (HPP)', 'type' => 'expense'],
            ['code' => '5101', 'name' => 'Beban Gaji & Tunjangan', 'type' => 'expense'], // [cite: 429]
            ['code' => '5102', 'name' => 'Beban Operasional Kantor', 'type' => 'expense'],
            ['code' => '5104', 'name' => 'Beban Penyusutan', 'type' => 'expense'],
        ];

        foreach ($accounts as $acc) {
            Account::updateOrCreate(
                ['code' => $acc['code']],
                $acc
            );
        }
    }
}