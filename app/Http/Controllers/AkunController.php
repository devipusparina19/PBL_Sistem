<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    // Tampilkan daftar akun
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.manajemen_akun', compact('users'));
    }

    // Form tambah akun
    public function create()
    {
        return view('admin.akun_create');
    }

    // Simpan akun baru
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:6|confirmed',
            'role'              => 'required|string',
            'nim_nip'           => 'nullable|string|unique:users,nim_nip',
            'kelas'             => 'nullable|string',
            'role_kelompok'     => 'nullable|string',
            'role_di_kelompok'  => 'nullable|string',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'role'              => $request->role,
            'nim_nip'           => $request->nim_nip,
            'kelas'             => $request->kelas,
            'role_kelompok'     => $request->role_kelompok,
            'role_di_kelompok'  => $request->role_di_kelompok,
        ]);

        // ✅ AUTO REGISTER DOSEN
        // Generate placeholder unik jika NIP/NIM kosong untuk mencegah error database unique constraint
        $nimNipDefault = $request->nim_nip ?? ($request->role == 'dosen' ? 'NIP-' . time() : 'NIM-' . time());

        if ($request->role === 'dosen') {
            Dosen::create([
                'nama'        => $request->name,
                'nip'         => $request->nim_nip ?? $nimNipDefault,
                'email'       => $request->email,
                'no_telp'     => '-',
                'kelas'       => '-',
                'mata_kuliah' => '-',
            ]);
        }

        // ✅ AUTO REGISTER MAHASISWA
        if ($request->role === 'mahasiswa') {
            $kelompokId = null;
            if ($request->role_kelompok) {
                // Cari ID Kelompok berdasarkan nomor kelompok (nama_kelompok) dan kelas
                $kelompok = Kelompok::where('nama_kelompok', 'LIKE', '%' . $request->role_kelompok)
                                    ->where('kelas', $request->kelas)
                                    ->first();
                $kelompokId = $kelompok ? $kelompok->id_kelompok : null;
            }

            Mahasiswa::create([
                'nim'         => $request->nim_nip ?? $nimNipDefault, // Pastikan NIM ada
                'nama'        => $request->name,
                'kelas'       => $request->kelas ?? '-',
                'angkatan'    => $request->nim_nip ? '20' . substr($request->nim_nip, 2, 2) : '2024',
                'email'       => $request->email,
                'password'    => Hash::make($request->password),
                'foto'        => null,
                'kelompok_id' => $kelompokId,
            ]);
        }

        return redirect()->route('akun.index')->with('success', 'Akun berhasil ditambahkan! Data otomatis masuk ke menu Data ' . ucfirst($request->role) . '.');
    }

    // Form edit akun
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.akun_edit', compact('user'));
    }

    // Update akun
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $user->id,
            'role'              => 'required|string',
            'nim_nip'           => 'nullable|string|unique:users,nim_nip,' . $user->id,
            'kelas'             => 'nullable|string',
            'role_kelompok'     => 'nullable|string',
            'role_di_kelompok'  => 'nullable|string',
        ]);

        $user->update([
            'name'              => $request->name,
            'email'             => $request->email,
            'role'              => $request->role,
            'nim_nip'           => $request->nim_nip,
            'kelas'             => $request->kelas,
            'role_kelompok'     => $request->role_kelompok,
            'role_di_kelompok'  => $request->role_di_kelompok,
        ]);

        // ✅ AUTO SYNC KE TABLE MAHASISWA / DOSEN
        $nimNipDefault = $request->nim_nip ?? ($request->role == 'dosen' ? 'NIP-' . time() : 'NIM-' . time());

        if ($request->role === 'dosen') {
            Dosen::updateOrCreate(
                ['email' => $request->email], 
                [
                    'nama'        => $request->name,
                    'nip'         => $request->nim_nip ?? $nimNipDefault,
                    'no_telp'     => '-',
                    'kelas'       => '-',
                    'mata_kuliah' => '-',
                ]
            );
        }

        if ($request->role === 'mahasiswa') {
            $kelompokId = null;
            if ($request->role_kelompok) {
                // Strict lookup matching RegisterController
                $namaKelompok = 'Kelompok ' . $request->role_kelompok . ' (' . $request->kelas . ')';
                $kelompok = Kelompok::where('nama_kelompok', $namaKelompok)
                                    ->where('kelas', $request->kelas)
                                    ->first();
                $kelompokId = $kelompok ? $kelompok->id_kelompok : null;
            }

            Mahasiswa::updateOrCreate(
                ['email' => $request->email], 
                [
                    'nim'         => $request->nim_nip ?? $nimNipDefault,
                    'nama'        => $request->name,
                    'kelas'       => $request->kelas ?? '-',
                    'angkatan'    => $request->nim_nip ? '20' . substr($request->nim_nip, 2, 2) : '2024',
                    'password'    => $request->filled('password') ? Hash::make($request->password) : $user->password,
                    'kelompok_id' => $kelompokId,
                ]
            );
        }

        return redirect()->route('akun.index')->with('success', 'Akun berhasil diperbarui! Data juga disinkronkan ke menu Data ' . ucfirst($request->role) . '.');
    }

    // Hapus akun
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('akun.index')->with('success', 'Akun berhasil dihapus!');
    }
}
