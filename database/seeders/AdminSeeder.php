<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->insert([
            'name' => 'Admin Padrão',
            'email' => 'admin@example.com',
            'password' => Hash::make('1234@5678'),
            'created_at' => now(),
        ]);
    }
}
