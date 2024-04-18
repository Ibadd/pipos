<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = User::create([
            'nama' => env('SUPERUSER_NAMA'),
            'uuid' => Str::uuid(),
            'email' => env('SUPERUSER_EMAIL'),
            'password' => Hash::make(env('SUPERUSER_PASSWORD')),
            'whatsapp' => env('SUPERUSER_PHONE'),
            'alamat' => env('SUPERUSER_ALAMAT')
        ]);

        $admin = User::create([
            'nama' => 'ADMIN',
            'uuid' => Str::uuid(),
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'whatsapp' => '085171737359',
            'alamat' => 'Malang'
        ]);

        $kasir = User::create([
            'nama' => 'KASIR',
            'uuid' => Str::uuid(),
            'email' => 'kasir@gmail.com',
            'password' => Hash::make('password'),
            'whatsapp' => '085171737359',
            'alamat' => 'Malang'
        ]);

        $gudang = User::create([
            'nama' => 'GUDANG',
            'uuid' => Str::uuid(),
            'email' => 'gudang@gmail.com',
            'password' => Hash::make('password'),
            'whatsapp' => '085171737359',
            'alamat' => 'Malang'
        ]);

        $superadmin->syncRoles('superadmin');
        $admin->syncRoles('admin');
        $kasir->syncRoles('kasir');
        $gudang->syncRoles('gudang');
    }
}
