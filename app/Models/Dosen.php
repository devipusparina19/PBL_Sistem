<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosens'; // ✅ Benar — pastikan nama tabel di migration juga 'dosens'

    protected $fillable = [
        'nama',
        'nip',
        'email',
        'no_telp',
        'kelas',
        'mata_kuliah', // ✅ disimpan dalam bentuk string, dipisah koma
    ];

    /**
     * Relasi ke tabel nilai
     */
    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'dosen_id'); // ✅ Sesuai konvensi relasi
    }

    /**
     * Akses mata kuliah sebagai array (otomatis di-decode)
     */
    public function getMataKuliahListAttribute()
    {
        if (empty($this->mata_kuliah)) {
            return [];
        }

        // ✅ Ini fleksibel — bisa pecah berdasarkan koma, titik koma, atau newline
        return preg_split('/[,;\n]+/', $this->mata_kuliah);
    }

    /**
     * Setter agar bisa menerima array dan menyimpannya jadi string
     */
    public function setMataKuliahListAttribute($value)
    {
        if (is_array($value)) {
            // ✅ Simpan sebagai string "A, B, C" tanpa elemen kosong
            $this->attributes['mata_kuliah'] = implode(', ', array_filter($value));
        } else {
            $this->attributes['mata_kuliah'] = $value;
        }
    }

    /**
     * Ambil mata kuliah dari tabel mata_kuliah berdasarkan NIP dosen
     * Return: Collection of MataKuliah
     */
    public function getMataKuliahDariTabelAttribute()
    {
        return MataKuliah::where('nip_dosen', 'LIKE', '%' . $this->nip . '%')->get();
    }

    /**
     * Ambil nama mata kuliah dari tabel mata_kuliah (array)
     */
    public function getNamaMataKuliahAttribute()
    {
        $mataKuliah = $this->mata_kuliah_dari_tabel;
        
        if ($mataKuliah->isEmpty()) {
            return [];
        }

        return $mataKuliah->pluck('nama_mk')->unique()->toArray();
    }

    /**
     * Ambil nama mata kuliah dalam format string
     */
    public function getNamaMataKuliahStringAttribute()
    {
        $names = $this->nama_mata_kuliah;
        
        if (empty($names)) {
            return '';
        }

        return implode(', ', $names);
    }
}
