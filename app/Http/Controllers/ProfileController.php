<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
<<<<<<< HEAD
    // hapus foto lama kalau ada
    if ($user->photo && file_exists(public_path($user->photo))) {
        unlink(public_path($user->photo));
=======
            // Hapus foto lama jika ada
            if ($user->photo && file_exists(public_path($user->photo))) {
                unlink(public_path($user->photo));
            }

            $filename = time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('uploads/profile'), $filename);
            $data['photo'] = 'uploads/profile/' . $filename;
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui!');
>>>>>>> 77ae7f0558866fb536e7c790c66db044f33b8084
    }

    $filename = time() . '.' . $request->photo->extension();
    $request->photo->move(public_path('uploads/profile'), $filename);
    $user->photo = 'uploads/profile/' . $filename;
}
}
}