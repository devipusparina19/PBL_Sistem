<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Update photo jika ada file yang diupload
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo && file_exists(public_path($user->photo))) {
                unlink(public_path($user->photo));
            }

            // Simpan foto baru
            $filename = time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('uploads/profile'), $filename);
            $data['photo'] = 'uploads/profile/' . $filename;
        }

        // Update data user
        $user->update($data);

        // ✅ SYNC KE TABEL MAHASISWA
        if ($user->role == 'mahasiswa' && $user->nim_nip) {
            $mhs = \App\Models\Mahasiswa::where('nim', $user->nim_nip)->first();
            if ($mhs) {
                 $mhsData = ['nama' => $request->name, 'email' => $request->email];
                 if ($request->filled('password')) {
                     $mhsData['password'] = Hash::make($request->password);
                 }
                 $mhs->update($mhsData);
            }
        }
        // ✅ SYNC KE TABEL DOSEN
        elseif ($user->role == 'dosen' && $user->nim_nip) {
             $dsn = \App\Models\Dosen::where('nip', $user->nim_nip)->first();
             if ($dsn) {
                 $dsn->update(['nama' => $request->name, 'email' => $request->email]);
             }
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
