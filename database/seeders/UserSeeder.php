<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'), // 🔥 ganti password
            'profile_photo' => null, // bisa isi path foto kalau ada
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
    }
}
