<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        // Check if logged-in user is a student
        if (auth()->user()->role === 'mahasiswa') {
            // Get student's class from users table
            $studentKelas = auth()->user()->kelas;
            
            if (!$studentKelas) {
                return view('mata_kuliah.index', [
                    'mataKuliahList' => collect(),
                    'isStudent' => true,
                    'studentKelas' => null
                ]);
            }
            
            // Get courses for student's class
            $mataKuliahList = MataKuliah::where('kelas', $studentKelas)
                ->orderBy('nama_mk', 'asc')
                ->get();
            
            return view('mata_kuliah.index', [
                'mataKuliahList' => $mataKuliahList,
                'isStudent' => true,
                'studentKelas' => $studentKelas
            ]);
        }
        
        // For admin/dosen: show all classes
        $kelasList = ['3A', '3B', '3C', '3D', '3E'];
        $mataKuliahByKelas = [];

        foreach ($kelasList as $kelas) {
            $mataKuliahByKelas[$kelas] = MataKuliah::where('kelas', $kelas)
                ->orderBy('nama_mk', 'asc')
                ->get();
        }

        return view('mata_kuliah.index', compact('mataKuliahByKelas', 'kelasList'));
    }


    public function showByKelas($kelas)
    {
        if (!in_array($kelas, ['3A', '3B', '3C', '3D', '3E'])) {
            abort(404);
        }

        $mataKuliah = MataKuliah::where('kelas', $kelas)
            ->orderBy('nama_mk', 'asc')
            ->paginate(10);

        return view('mata_kuliah.kelas', compact('mataKuliah', 'kelas'));
    }

    public function showDetail($id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);
        
        // Get all students from the same class
        $students = \App\Models\User::where('role', 'mahasiswa')
            ->where('kelas', $mataKuliah->kelas)
            ->orderBy('name', 'asc')
            ->get();
        
        return view('mata_kuliah.detail', compact('mataKuliah', 'students'));
    }

    public function create(Request $request)
    {
        $kelasDefault = $request->query('kelas', '3A');
        return view('mata_kuliah.create', compact('kelasDefault'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mk' => 'required|string|max:50',
            'nama_mk' => 'required|string|max:255',
            'kelas' => 'required|in:3A,3B,3C,3D,3E',
            'nip_dosen' => 'required|array|min:1',
            'nip_dosen.*' => 'nullable|string|max:30',
        ]);

        $validated['nip_dosen'] = implode(',', array_filter($validated['nip_dosen']));

        // ✅ Tambahkan pemeriksaan duplikasi (kode_mk + kelas)
        $exists = MataKuliah::where('kode_mk', $validated['kode_mk'])
            ->where('kelas', $validated['kelas'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['kode_mk' => 'Kode mata kuliah sudah terdaftar di kelas ini.'])
                ->withInput();
        }

        try {
            MataKuliah::create($validated);
        } catch (QueryException $e) {
            // ✅ Tangani error duplikat agar tidak muncul 500
            if ($e->errorInfo[1] == 1062) {
                return back()
                    ->withErrors(['kode_mk' => 'Kode mata kuliah sudah digunakan.'])
                    ->withInput();
            }
            throw $e;
        }

        if ($request->has('kelas')) {
            return redirect()->route('mata_kuliah.kelas', $request->kelas)
                ->with('success', 'Mata kuliah berhasil ditambahkan.');
        }

        return redirect()->route('mata_kuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function show(MataKuliah $mataKuliah)
    {
        return view('mata_kuliah.show', compact('mataKuliah'));
    }

    public function edit(MataKuliah $mataKuliah)
    {
        return view('mata_kuliah.edit', compact('mataKuliah'));
    }

    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $validated = $request->validate([
            'kode_mk' => 'required|string|max:50',
            'nama_mk' => 'required|string|max:255',
            'kelas' => 'required|in:3A,3B,3C,3D,3E',
            'nip_dosen' => 'required|array',
            'nip_dosen.*' => 'required|string|max:30',
        ]);

        $validated['nip_dosen'] = implode(',', $validated['nip_dosen']);

        // ✅ Cegah duplikasi saat update (kecuali baris yang sama)
        $exists = MataKuliah::where('kode_mk', $validated['kode_mk'])
            ->where('kelas', $validated['kelas'])
            ->where('id', '!=', $mataKuliah->id)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['kode_mk' => 'Kode mata kuliah sudah digunakan di kelas ini.'])
                ->withInput();
        }

        $mataKuliah->update($validated);

        if ($request->has('kelas')) {
            return redirect()->route('mata_kuliah.kelas', $request->kelas)
                ->with('success', 'Data mata kuliah berhasil diperbarui.');
        }

        return redirect()->route('mata_kuliah.index')
            ->with('success', 'Data mata kuliah berhasil diperbarui.');
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        $kelas = $mataKuliah->kelas;
        $mataKuliah->delete();

        if (request()->server('HTTP_REFERER') && str_contains(request()->server('HTTP_REFERER'), 'mata_kuliah/kelas/')) {
            return redirect()->route('mata_kuliah.kelas', $kelas)
                ->with('success', 'Mata kuliah berhasil dihapus.');
        }

        return redirect()->route('mata_kuliah.index')
            ->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
