<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'username' => 'admin@'.env('APP_NAME'),
            'password' => Hash::make('admin'),
            'role' => 'admin',
            'gender' => 'Admin',
            'picture' => 'public/Users_Pictures/admin'
        ]);
    }
}
