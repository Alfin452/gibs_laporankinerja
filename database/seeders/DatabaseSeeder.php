<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ActivityType;
use App\Models\AppSetting;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gibs.sch.id', // Email login admin
            'password' => Hash::make('password'), // Password default
            'role' => 'admin',
            'nip' => '000000'
        ]);

        // 2. Setting Default Lokasi (Contoh Koordinat GIBS, nanti diedit di admin)
        AppSetting::create([
            'school_latitude' =>  -3.229683,
            'school_longitude' =>  114.598840,
            'radius_meters' => 100,
        ]);

        // 3. Buat Master Data Kegiatan (K2 - K20)
        $activities = [
            ['code' => 'K2', 'name' => 'Substitute Teacher', 'input_type' => 'numeric'], // K2 Numeric
            ['code' => 'K3', 'name' => 'Morning Briefing', 'input_type' => 'boolean'],
            ['code' => 'K4', 'name' => 'Ceremony', 'input_type' => 'boolean'],
            ['code' => 'K5', 'name' => 'Session', 'input_type' => 'boolean'],
            ['code' => 'K6', 'name' => 'Sholat Dhuha', 'input_type' => 'boolean'],
            ['code' => 'K7', 'name' => 'Sholat Dzuhur', 'input_type' => 'boolean'],
            ['code' => 'K8', 'name' => 'Physical Fitness', 'input_type' => 'boolean'],
        ];

        // Tambah K9 sampai K20 (Placeholder)
        for ($i = 9; $i <= 20; $i++) {
            $activities[] = [
                'code' => 'K' . $i,
                'name' => 'Kegiatan K' . $i,
                'input_type' => 'boolean'
            ];
        }

        foreach ($activities as $act) {
            ActivityType::create($act);
        }

        // 4. Import Data Guru dari Excel (Total 41 Guru)
        $teachers = [
            'H. M. Amin, S.Pd.I., M.A.',
            'M. Zamrony, Lc., M.Ag.',
            'M. Alfi Hidayat, S.Th.I.',
            'M. Zainul Wathani, S.Ei., M.Si.',
            'Randi Ahmad Irwanto, S.Pd., Gr., M.Pd.',
            'Siti Mukhalafatun, S.Pd.',
            'Nopi Ariani, S.Pd.',
            'Ellina Normarisda, S.Pd.',
            'Muhammad Ramdhani, S.Pd.',
            'Azhari Wahyo Widodo, S.Pd., Gr., M.Pd.',
            'Ade Syaputra, S.S.',
            'Aulya Rachmadia Mayta Putri, S.Pd.',
            'M. Rijali Riyadi, S.Pd.',
            'Awaludin, S.Pd., Gr., M.Pd.',
            'Nisrina Adriyanthi, S.Pd., Gr.',
            'Annisa Rezma Sari, S.Pd.',
            'Niko Rahmad Aprilyanto, S.Pd., Gr.',
            'Dhea Amanda, S.Si., Gr.',
            'Siti Zubaidah, S.Pd.',
            'Kurniawan, S.T., Gr.',
            'Siti Nurhaliza, S.Pd.',
            'Widhi Astuti, S.Pd., Gr.',
            'Manesta Edelweis Jingga, S.Si.',
            'M. Anshori, S.Pd., Gr., M.Pd.',
            'Tony Prastio Aribowo, S.Pd., Gr.',
            'Noremilia, S.Pd.',
            'Melda Yanti, S.Pd., Gr.',
            'Isma Imanda, S.Pd.',
            'Irma Dwina, M.Pd.',
            'Rahmat Dwi Purwanto, S.Pd., Gr., M.Pd.',
            'Dhea Khairiyah, S.Pd.',
            'Ahmad Sappauni',
            'Ahmad Rizqan, S.Pd.',
            'M. Mustain, S.Si.',
            'Quine Zahva Novenia, S.Pd.',
            'Endik Panjaitan, S.Pd., Gr.',
            'Akhmad Syarwani, S.Pd.',
            'Noor Bayti, S.Pd.',
            'Norhidayah, S.Pd.',
            'Siti Raudah, S.Pd.',
            'Rahman'
        ];

        foreach ($teachers as $index => $name) {
            // Buat email dummy: nama_depan@gibs.sch.id
            $firstName = strtolower(explode(' ', trim($name))[0]);
            // Bersihkan karakter aneh di email
            $cleanName = preg_replace('/[^a-z0-9]/', '', $firstName);
            $email = $cleanName . ($index + 1) . '@gibs.sch.id'; // Tambah index biar unik

            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('12345678'), // Password Guru
                'role' => 'guru',
                'nip' => '2026' . str_pad($index + 1, 4, '0', STR_PAD_LEFT) // NIP Dummy
            ]);
        }
    }
}
