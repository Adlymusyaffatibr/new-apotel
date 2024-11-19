<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'nama' => 'member',
            'email' => 'member@gmail.com',
            'password' => Hash::make('member'),
            'role' => 'member',
        ]);
        User::create([
            'nama' => 'admin Apotek',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('adminapotek'),
            'role' => 'admin',
        ]);
    }
}
