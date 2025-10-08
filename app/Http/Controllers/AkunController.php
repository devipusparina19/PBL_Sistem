<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|string'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('akun.index')->with('success', 'Akun berhasil ditambahkan!');
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
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|string',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('akun.index')->with('success', 'Akun berhasil diperbarui!');
    }

    // Hapus akun
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('akun.index')->with('success', 'Akun berhasil dihapus!');
    }
}
