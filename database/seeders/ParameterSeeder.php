<?php

namespace Database\Seeders;

use App\Models\Aturan;
use App\Models\Kendaraan;
use App\Models\Parameter;
use App\Models\User;
use Illuminate\Database\Seeder;

class ParameterSeeder extends Seeder
{
    public function run()
    {
        // buat user dengan role admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
        ]);

        // buat user dengan role pemilik sebanyak 3
        $users = [
            ['name' => 'Ahmad', 'email' => 'ahmad@gmail.com', 'password' => bcrypt('12345678'), 'role' => 'pemilik'],
            ['name' => 'Budi', 'email' => 'budi@gmail.com', 'password' => bcrypt('12345678'), 'role' => 'pemilik'],
            ['name' => 'Charlie', 'email' => 'charlie@gmail.com', 'password' => bcrypt('12345678'), 'role' => 'pemilik'],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // buat kendaraan dengan pemilik yang sudah dibuat
        $kendaraans = [
            ['nomor_plat' => 'B1234CD', 'tahun_pembuatan' => 2010, 'nama_pemilik' => 'Ahmad', 'alamat_pemilik' => 'Jl. Merdeka No. 1', 'no_telepon_pemilik' => '081234567890', 'status_kir' => 'aktif', 'user_id' => 2],
            ['nomor_plat' => 'B5678EF', 'tahun_pembuatan' => 2012, 'nama_pemilik' => 'Budi', 'alamat_pemilik' => 'Jl. Sudirman No. 2', 'no_telepon_pemilik' => '081234567891', 'status_kir' => 'aktif', 'user_id' => 3],
            ['nomor_plat' => 'B9101GH', 'tahun_pembuatan' => 2015, 'nama_pemilik' => 'Charlie', 'alamat_pemilik' => 'Jl. Thamrin No. 3', 'no_telepon_pemilik' => '081234567892', 'status_kir' => 'aktif', 'user_id' => 4],
        ];

        foreach ($kendaraans as $kendaraan) {
            Kendaraan::create($kendaraan);
        }

        $parameters = [
            ['nama' => 'rem', 'deskripsi' => 'Komponen untuk memperlambat atau menghentikan kendaraan.'],
            ['nama' => 'lampu', 'deskripsi' => 'Komponen untuk penerangan kendaraan.'],
            ['nama' => 'ban', 'deskripsi' => 'Komponen yang bersentuhan langsung dengan permukaan jalan.'],
        ];

        foreach ($parameters as $parameter) {
            Parameter::create($parameter);
        }

        $aturans = [
            ['parameter_id' => 1, 'nama_aturan' => 'Rem berfungsi dengan baik', 'cf_value' => 0.8],
            ['parameter_id' => 1, 'nama_aturan' => 'Rem tidak berfungsi dengan baik', 'cf_value' => 0.2],
            ['parameter_id' => 1, 'nama_aturan' => 'Rem perlu diganti', 'cf_value' => 0.5],
            ['parameter_id' => 1, 'nama_aturan' => 'Rem perlu diservis', 'cf_value' => 0.5],
            ['parameter_id' => 2, 'nama_aturan' => 'Lampu berfungsi dengan baik', 'cf_value' => 0.8],
            ['parameter_id' => 2, 'nama_aturan' => 'Lampu tidak berfungsi dengan baik', 'cf_value' => 0.2],
            ['parameter_id' => 2, 'nama_aturan' => 'Lampu perlu diganti', 'cf_value' => 0.5],
            ['parameter_id' => 2, 'nama_aturan' => 'Lampu perlu diservis', 'cf_value' => 0.5],
            ['parameter_id' => 3, 'nama_aturan' => 'Ban berfungsi dengan baik', 'cf_value' => 0.8],
            ['parameter_id' => 3, 'nama_aturan' => 'Ban tidak berfungsi dengan baik', 'cf_value' => 0.2],
            ['parameter_id' => 3, 'nama_aturan' => 'Ban perlu diganti', 'cf_value' => 0.5],
            ['parameter_id' => 3, 'nama_aturan' => 'Ban perlu diservis', 'cf_value' => 0.5],
        ];

        foreach ($aturans as $aturan) {
            Aturan::create($aturan);
        }
    }
}
