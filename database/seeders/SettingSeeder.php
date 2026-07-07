<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'app_name' => 'TailorPro',
            'copyright' => 'TailorPro | 2026',
            'login_title' => 'Portal Tailor',
            'keywords' => 'tailor, penjahit, manajemen produksi, pesanan pakaian',
            'description' => 'Sistem Manajemen Produksi dan Pelanggan untuk Usaha Tailor Premium.',
            'logo' => 'logo.png',
        ]);
    }
}
