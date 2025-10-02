<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    // Nama tabel (opsional, kalau pakai konvensi "contacts" tidak perlu ditulis)
    protected $table = 'contacts';

    // Kolom yang boleh diisi mass assignment
    protected $fillable = [
        'nama',
        'email',
        'pesan',
    ];
}
