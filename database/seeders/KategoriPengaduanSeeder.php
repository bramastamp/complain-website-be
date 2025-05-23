<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KategoriPengaduan;

class KategoriPengaduanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KategoriPengaduan::insert([
            ['nama_kategori' => 'Fasilitas Sekolah', 'deskripsi' => 'Masalah terkait fasilitas seperti bangku, AC, toilet, dll'],
            ['nama_kategori' => 'Guru dan Staff', 'deskripsi' => 'Keluhan atau masukan terhadap guru atau staf sekolah'],
            ['nama_kategori' => 'Kegiatan Belajar', 'deskripsi' => 'Saran atau kritik mengenai metode pengajaran, tugas, atau materi'],
            ['nama_kategori' => 'Lain-lain', 'deskripsi' => 'Masukan yang tidak masuk kategori lainnya'],
        ]);
    }
}
