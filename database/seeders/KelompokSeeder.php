<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kelompok;

class KelompokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelasList = ['3A', '3B', '3C', '3D', '3E'];

        foreach ($kelasList as $kelas) {
            for ($i = 1; $i <= 3; $i++) {
                Kelompok::create([
                    'kode_mk' => 'PBL-' . $kelas . '-' . $i,
                    'nama_kelompok' => 'Kelompok ' . $i . ' (' . $kelas . ')',
                    'judul_proyek' => 'Proyek PBL ' . $kelas . ' ' . $i,
                    'kelas' => $kelas,
                    'ketua_id' => null,
                ]);
            }
        }
    }
}
