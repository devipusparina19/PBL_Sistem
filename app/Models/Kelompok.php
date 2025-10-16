<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    // Nama tabel sesuai database
    protected $table = 'kelompoks';

    // Primary key
    protected $primaryKey = 'id_kelompok';
    public $incrementing = true;
    protected $keyType = 'int';

    // Kolom yang boleh diisi
    protected $fillable = [
        'kode_mk',
        'nama_kelompok',
        'kelas',
        'judul_proyek',
        'nip',
        'deskripsi',
        // Nilai kelompok
        'pemrograman_web',
        'integrasi_sistem',
        'pengambilan_keputusan',
        'it_proyek',
        'kontribusi_kelompok',
        'penilaian_dosen',
        'hasil_akhir',
    ];

    // Jika tabel memiliki kolom created_at dan updated_at
    public $timestamps = true;
}
