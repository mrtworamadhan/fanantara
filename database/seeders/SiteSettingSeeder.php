<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Informasi Aplikasi
            [
                'key' => 'app_name',
                'value' => 'Fanantara',
                'label' => 'Nama Aplikasi',
                'type' => 'text',
            ],
            [
                'key' => 'app_tagline',
                'value' => 'Koperasi Multi Pihak',
                'label' => 'Tagline',
                'type' => 'text',
            ],
            [
                'key' => 'app_description',
                'value' => 'Koperasi Multi Pihak yang bergerak dalam berbagai bidang usaha untuk kesejahteraan anggota dan masyarakat.',
                'label' => 'Deskripsi Singkat',
                'type' => 'text',
            ],

            // Tentang Section (Homepage)
            [
                'key' => 'about_paragraph_1',
                'value' => 'adalah Koperasi Multi Pihak yang menghubungkan berbagai elemen masyarakat dalam satu ekosistem ekonomi yang saling menguntungkan. Kami hadir untuk menjembatani kebutuhan anggota dengan solusi keuangan yang mudah, aman, dan terpercaya.',
                'label' => 'Tentang - Paragraf 1',
                'type' => 'textarea',
            ],
            [
                'key' => 'about_paragraph_2',
                'value' => 'Melalui aplikasi mobile dan layanan digital, setiap anggota dapat mengakses simpanan, pinjaman, serta berbagai produk koperasi kapan saja dan di mana saja. Fanantara berkomitmen untuk mendorong pertumbuhan ekonomi inklusif bagi seluruh anggota dan masyarakat Indonesia.',
                'label' => 'Tentang - Paragraf 2',
                'type' => 'textarea',
            ],

            // Visi Misi (About Page)
            [
                'key' => 'visi',
                'value' => 'Menjadi koperasi multi pihak terdepan yang memberdayakan seluruh anggota melalui ekosistem ekonomi yang inklusif, adil, dan berkelanjutan.',
                'label' => 'Visi',
                'type' => 'textarea',
            ],
            [
                'key' => 'misi',
                'value' => json_encode([
                    'Memfasilitasi akses produk berkualitas dengan harga kompetitif',
                    'Mengelola keuangan transparan dengan pembagian SHU yang adil',
                    'Membangun jaringan yang saling menguntungkan',
                ]),
                'label' => 'Misi',
                'type' => 'json',
            ],

            // Kontak
            [
                'key' => 'contact_whatsapp',
                'value' => '6281234567890',
                'label' => 'Nomor WhatsApp',
                'type' => 'text',
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@fanantara.com',
                'label' => 'Email',
                'type' => 'text',
            ],
            [
                'key' => 'contact_address',
                'value' => 'Jl. Contoh Alamat No. 123, Kota, Provinsi 12345',
                'label' => 'Alamat Kantor',
                'type' => 'textarea',
            ],

            // Dokumen
            [
                'key' => 'adart_file',
                'value' => 'documents/adart.pdf',
                'label' => 'File AD/ART (PDF)',
                'type' => 'file',
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
