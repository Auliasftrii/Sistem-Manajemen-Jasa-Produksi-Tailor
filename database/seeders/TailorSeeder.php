<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TailorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pegawai = \App\Models\User::where('role', 'Pegawai')->first();
        
        if ($pegawai) {
            \App\Models\Tailor::create([
                'user_id' => $pegawai->id,
                'specialization' => 'Umum',
                'is_available' => true,
            ]);
        }
    }
}
