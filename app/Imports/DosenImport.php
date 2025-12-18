<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DosenImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $kelas;

    public function __construct($kelas)
    {
        $this->kelas = $kelas;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Pastikan NIP tidak kosong
        if (empty($row['nip'])) {
            return null;
        }

        // Cek duplikat data dosen (NIP & Kelas sama)
        $existingDosen = Dosen::where('nip', $row['nip'])
                              ->where('kelas', $this->kelas)
                              ->first();

        if ($existingDosen) {
            return null; // Skip jika sudah ada
        }

        // Create Dosen
        $dosen = Dosen::create([
            'nama'        => $row['nama'],
            'nip'         => $row['nip'],
            'email'       => $row['email'],
            'no_telp'     => $row['no_telp'] ?? '', // Optional - can be set manually
            'kelas'       => $this->kelas,
            'mata_kuliah' => $row['mata_kuliah'] ?? '', // Optional - can be set manually
        ]);

        // Auto Create User Account
        User::firstOrCreate(
            ['email' => $row['email']],
            [
                'name'     => $row['nama'],
                'password' => Hash::make('123456'), // Default password
                'role'     => 'dosen',
                'nim_nip'  => $row['nip'],
            ]
        );

        return $dosen;
    }

    public function rules(): array
    {
        return [
            'nama'  => 'required',
            'nip'   => 'required',
            'email' => 'required|email',
            // no_telp dan mata_kuliah optional - bisa diisi manual oleh admin
        ];
    }
}
