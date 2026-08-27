<?php

namespace Database\Seeders;

use App\Models\Polyclinic;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::create(['role_name' => 'Admin']);
        $nurseRole = Role::create(['role_name' => 'Perawat']);
        $doctorRole = Role::create(['role_name' => 'Dokter']);

        $nonNakes = Polyclinic::create(['poly_name' => 'Non Nakes']);
        $PoliUmum = Polyclinic::create(['poly_name' => 'Poli Umum']);
        Polyclinic::create(['poly_name' => 'Poli Jantung']);
        Polyclinic::create(['poly_name' => 'Poli Penyakit Dalam']);

        User::create([
            'role_id' => $adminRole->id,
            'poly_id' => $nonNakes->id,
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);

        User::create([
            'role_id' => $nurseRole->id,
            'poly_id' => $PoliUmum->id,
            'name' => 'Siti Perawat',
            'email' => 'perawat@gmail.com',
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);

        User::create([
            'role_id' => $doctorRole->id,
            'poly_id' => $PoliUmum->id,
            'name' => 'dr. Budi Santoso',
            'email' => 'dokter@gmail.com',
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);
    }
}
