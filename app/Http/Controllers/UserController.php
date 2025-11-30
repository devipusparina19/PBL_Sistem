public function store(Request $request)
{
    $request->validate([
        'name'      => 'required|string|max:255',
        'nim_nip'   => 'required|string|max:50|unique:users,nim_nip',
        'email'     => [
            'required',
            'string',
            'email',
            'max:255',
            'unique:users',
            function ($attribute, $value, $fail) {
                if (!str_ends_with($value, '@politala.ac.id')) {
                    $fail('Hanya email Politala yang diperbolehkan mendaftar.');
                }
            },
        ],
        'password'  => 'required|string|min:8|confirmed',
        'role'      => 'required|string|in:mahasiswa,dosen,admin,koordinator_pbl,koordinator_prodi',
        'kelas'     => 'nullable|string|max:100',
        'role_kelompok' => 'nullable|integer',
        'role_di_kelompok' => 'nullable|string|max:50',
    ]);

    // Simpan data ke tabel users
    $user = User::create([
        'name'      => $request->name,
        'nim_nip'   => $request->nim_nip,
        'email'     => $request->email,
        'password'  => Hash::make($request->password),
        'role'      => $request->role,
    ]);

    // Simpan ke tabel sesuai role
    switch ($request->role) {
        case 'mahasiswa':
            \App\Models\Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $request->nim_nip,
                'nama' => $request->name,
                'kelas' => $request->kelas ?? '-',
                'email' => $request->email,
                'kelompok_id' => $request->role_kelompok ?? null,
                'role_di_kelompok' => $request->role_di_kelompok ?? 'anggota',
                'angkatan' => date('Y'),
            ]);
            break;

        case 'dosen':
            \App\Models\Dosen::create([
                'user_id' => $user->id,
                'nip' => $request->nim_nip,
                'nama' => $request->name,
                'email' => $request->email,
            ]);
            break;
    }

    Auth::login($user);

    return $this->redirectToDashboard($user);
}
