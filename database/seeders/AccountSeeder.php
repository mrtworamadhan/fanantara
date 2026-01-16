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
            ['code' => '1103', 'name' => 'Piutang Usaha Anggota', 'type' => 'asset'], 
            ['code' => '1104', 'name' => 'Piutang Usaha Non-Anggota', 'type' => 'asset'],
            ['code' => '1105', 'name' => 'Piutang Simpanan (Anggota)', 'type' => 'asset'],
            ['code' => '1106', 'name' => 'Persediaan Barang Dagang', 'type' => 'asset'], 
            ['code' => '1107', 'name' => 'Biaya Dibayar Dimuka', 'type' => 'asset'],
            
            // 1.2 Aset Tidak Lancar
            ['code' => '1201', 'name' => 'Aset Tetap - Peralatan', 'type' => 'asset'],
            ['code' => '1202', 'name' => 'Akumulasi Penyusutan Peralatan', 'type' => 'asset'], 
            ['code' => '1203', 'name' => 'Aset Tetap - Bangunan', 'type' => 'asset'],
            ['code' => '1204', 'name' => 'Akumulasi Penyusutan Bangunan', 'type' => 'asset'],
            ['code' => '1205', 'name' => 'Aset Tetap - Kendaraan', 'type' => 'asset'],
            ['code' => '1206', 'name' => 'Akumulasi Penyusutan Kendaraan', 'type' => 'asset'],

            // --- 2. LIABILITAS (LIABILITIES) ---
            // 2.1 Jangka Pendek
            ['code' => '2101', 'name' => 'Hutang Usaha', 'type' => 'liability'], 
            ['code' => '2102', 'name' => 'Simpanan Sukarela (Jangka Pendek)', 'type' => 'liability'],
            ['code' => '2103', 'name' => 'SHU Belum Dibagikan', 'type' => 'liability'],
            ['code' => '2104', 'name' => 'Beban Masih Harus Dibayar', 'type' => 'liability'],
            
            // 2.2 Jangka Panjang
            ['code' => '2201', 'name' => 'Simpanan Berjangka (Anggota)', 'type' => 'liability'],

            // --- 3. EKUITAS (EQUITY) ---
            // 3.1 Modal Anggota (HANYA POKOK & WAJIB)
            ['code' => '3101', 'name' => 'Simpanan Pokok', 'type' => 'equity'],
            ['code' => '3102', 'name' => 'Simpanan Wajib', 'type' => 'equity'],
            
            ['code' => '3103', 'name' => 'Dana Hibah', 'type' => 'equity'],

            // 3.2 Cadangan & SHU
            ['code' => '3201', 'name' => 'Cadangan SHU', 'type' => 'equity'],
            ['code' => '3202', 'name' => 'Cadangan Umum Koperasi', 'type' => 'equity'],
            ['code' => '3301', 'name' => 'SHU Tahun Berjalan', 'type' => 'equity'],

            // --- 4. PENDAPATAN (REVENUE) ---
            // 4.1 Pendapatan Anggota
            ['code' => '4101', 'name' => 'Pendapatan Jasa/Penjualan (Anggota)', 'type' => 'revenue'],
            ['code' => '4102', 'name' => 'Pendapatan Toko (Anggota)', 'type' => 'revenue'],
            
            // 4.2 Pendapatan Non-Anggota
            ['code' => '4201', 'name' => 'Pendapatan Jasa/Penjualan (Non-Anggota)', 'type' => 'revenue'], 
            ['code' => '4301', 'name' => 'Pendapatan Lain-lain', 'type' => 'revenue'],

            // --- 5. BEBAN (EXPENSES) ---
            ['code' => '5100', 'name' => 'Harga Pokok Penjualan (HPP)', 'type' => 'expense'],
            ['code' => '5101', 'name' => 'Beban Gaji & Tunjangan', 'type' => 'expense'], 
            ['code' => '5102', 'name' => 'Beban Operasional Kantor', 'type' => 'expense'],
            ['code' => '5104', 'name' => 'Beban Penyusutan', 'type' => 'expense'],
            ['code' => '5105', 'name' => 'Beban Rapat Anggota (RAT)', 'type' => 'expense'],
            ['code' => '5106', 'name' => 'Beban Pendidikan Perkoperasian', 'type' => 'expense'],

        ];

        foreach ($accounts as $acc) {
            Account::updateOrCreate(
                ['code' => $acc['code']],
                $acc
            );
        }
    }
}