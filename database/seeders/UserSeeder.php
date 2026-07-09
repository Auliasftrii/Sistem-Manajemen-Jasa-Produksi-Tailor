<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Aulia Safitri',
                'email' => 'aulia@gmail.com',
                'role' => 'Superadmin',
            ],
            [
                'name' => 'Nur Aulia',
                'email' => 'admin@gmail.com',
                'role' => 'Admin',
            ],
            [
                'name' => 'Budi Penjahit',
                'email' => 'pegawai@gmail.com',
                'role' => 'Pegawai',
            ],
        ];

        foreach ($users as $user) {
            if (User::where('email', $user['email'])->exists()) {
                continue;
            }

            User::factory()->create([
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
            ]);
        }
        
        // Add additional dummy users to reach minimal 5 data
        User::factory(3)->create(['role' => 'Pegawai']);
    }
}
