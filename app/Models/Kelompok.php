<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    // Nama tabel sesuai database
    protected $table = 'kelompok';

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
        'kelas',
    ];

    // Jika tabel memiliki kolom created_at dan updated_at
    public $timestamps = true;
    
    public function milestones()
{
    return $this->hasMany(Milestone::class, 'kelompok_id');
}

}
