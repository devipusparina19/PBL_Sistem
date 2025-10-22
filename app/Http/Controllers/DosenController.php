<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    /* ========================================================
     |  BAGIAN 1 — MANAJEMEN DATA DOSEN
     ======================================================== */

    public function index()
    {
        $kelasList = ['3A', '3B', '3C', '3D', '3E'];
        $dosenByKelas = [];

        foreach ($kelasList as $kelas) {
            $dosenByKelas[$kelas] = Dosen::where('kelas', $kelas)
                ->orderBy('nama', 'asc')
                ->get();
        }

        return view('data_dosen.index', compact('dosenByKelas', 'kelasList'));
    }

    public function showByKelas($kelas)
    {
        if (!in_array($kelas, ['3A', '3B', '3C', '3D', '3E'])) {
            abort(404);
        }

        $dosens = Dosen::where('kelas', $kelas)
            ->orderBy('nama', 'asc')
            ->paginate(15);

        return view('data_dosen.kelas', compact('dosens', 'kelas'));
    }

    public function create(Request $request)
    {
        $kelasDefault = $request->query('kelas', '3A');
        return view('data_dosen.create', compact('kelasDefault'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'nip'           => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) use ($request) {
                    // Cegah duplikat NIP di kelas yang sama
                    $exists = Dosen::where('kelas', $request->kelas)
                        ->where('nip', $value)
                        ->exists();
                    if ($exists) {
                        $fail('NIP sudah digunakan oleh dosen lain di kelas ' . $request->kelas . '.');
                    }
                },
            ],
            'email'         => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    // Cegah duplikat Email di kelas yang sama
                    $exists = Dosen::where('kelas', $request->kelas)
                        ->where('email', $value)
                        ->exists();
                    if ($exists) {
                        $fail('Email sudah digunakan oleh dosen lain di kelas ' . $request->kelas . '.');
                    }
                },
            ],
            'no_telp'       => 'nullable|string|max:20',
            'kelas'         => 'nullable|string|max:50',
            'mata_kuliah'   => 'required|array',
            'mata_kuliah.*' => 'string|max:100',
        ]);

        $mataKuliahGabung = implode(', ', $request->mata_kuliah);

        // Cegah duplikat persis seluruh kombinasi
        $exists = Dosen::where('nip', $request->nip)
            ->where('email', $request->email)
            ->where('kelas', $request->kelas)
            ->where('mata_kuliah', $mataKuliahGabung)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'nip' => 'Dosen dengan kombinasi data ini sudah terdaftar (NIP, Email, Kelas, dan Mata Kuliah sama persis).'
            ])->withInput();
        }

        Dosen::create([
            'nama'        => $request->nama,
            'nip'         => $request->nip,
            'email'       => $request->email,
            'no_telp'     => $request->no_telp,
            'kelas'       => $request->kelas,
            'mata_kuliah' => $mataKuliahGabung,
        ]);

        if ($request->filled('kelas')) {
            return redirect()->route('data_dosen.kelas', $request->kelas)
                ->with('success', 'Data dosen berhasil ditambahkan!');
        }

        return redirect()->route('data_dosen.index')
            ->with('success', 'Data dosen berhasil ditambahkan!');
    }

    public function show($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('data_dosen.show_data', compact('dosen'));
    }

    public function edit($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('data_dosen.edit', compact('dosen'));
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $request->validate([
            'nama'          => 'required|string|max:255',
            'nip'           => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) use ($request, $dosen) {
                    // Cegah duplikat NIP di kelas yang sama (kecuali dirinya)
                    $exists = Dosen::where('kelas', $request->kelas)
                        ->where('nip', $value)
                        ->where('id', '!=', $dosen->id)
                        ->exists();
                    if ($exists) {
                        $fail('NIP sudah digunakan oleh dosen lain di kelas ' . $request->kelas . '.');
                    }
                },
            ],
            'email'         => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($request, $dosen) {
                    // Cegah duplikat Email di kelas yang sama (kecuali dirinya)
                    $exists = Dosen::where('kelas', $request->kelas)
                        ->where('email', $value)
                        ->where('id', '!=', $dosen->id)
                        ->exists();
                    if ($exists) {
                        $fail('Email sudah digunakan oleh dosen lain di kelas ' . $request->kelas . '.');
                    }
                },
            ],
            'no_telp'       => 'nullable|string|max:20',
            'kelas'         => 'nullable|string|max:50',
            'mata_kuliah'   => 'required|array',
            'mata_kuliah.*' => 'string|max:100',
        ]);

        $mataKuliahGabung = implode(', ', $request->mata_kuliah);

        // Cegah kombinasi data identik selain dirinya
        $exists = Dosen::where('nip', $request->nip)
            ->where('email', $request->email)
            ->where('kelas', $request->kelas)
            ->where('mata_kuliah', $mataKuliahGabung)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'nip' => 'Dosen dengan kombinasi data ini sudah terdaftar (NIP, Email, Kelas, dan Mata Kuliah sama persis).'
            ])->withInput();
        }

        $dosen->update([
            'nama'        => $request->nama,
            'nip'         => $request->nip,
            'email'       => $request->email,
            'no_telp'     => $request->no_telp,
            'kelas'       => $request->kelas,
            'mata_kuliah' => $mataKuliahGabung,
        ]);

        if ($request->filled('kelas')) {
            return redirect()->route('data_dosen.kelas', $request->kelas)
                ->with('success', 'Data dosen berhasil diperbarui!');
        }

        return redirect()->route('data_dosen.index')
            ->with('success', 'Data dosen berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $kelas = $dosen->kelas;
        $dosen->delete();

        if (request()->server('HTTP_REFERER') && str_contains(request()->server('HTTP_REFERER'), 'data_dosen/kelas/')) {
            return redirect()->route('data_dosen.kelas', $kelas)
                ->with('success', 'Data dosen berhasil dihapus!');
        }

        return redirect()->route('data_dosen.index')
            ->with('success', 'Data dosen berhasil dihapus!');
    }

    /* ========================================================
     |  BAGIAN 2 — FITUR TAMBAHAN: INPUT NILAI MAHASISWA
     ======================================================== */

    public function inputNilai()
    {
        $mahasiswa = Mahasiswa::orderBy('nama', 'asc')->get();
        $nilai = Nilai::with('mahasiswa')->latest()->get();

        return view('dosen.input_nilai', compact('mahasiswa', 'nilai'));
    }

    public function storeNilai(Request $request)
    {
        $request->validate([
            'mahasiswa_id'      => 'required|exists:mahasiswas,id',
            'laporan'           => 'required|numeric|min:0|max:100',
            'presentasi'        => 'required|numeric|min:0|max:100',
            'kontribusi'        => 'required|numeric|min:0|max:100',
            'tanggal_penilaian' => 'required|date',
        ]);

        Nilai::create([
            'mahasiswa_id'      => $request->mahasiswa_id,
            'laporan'           => $request->laporan,
            'presentasi'        => $request->presentasi,
            'kontribusi'        => $request->kontribusi,
            'tanggal_penilaian' => $request->tanggal_penilaian,
        ]);

        return back()->with('success', 'Nilai mahasiswa berhasil disimpan.');
    }
}
