<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    // ===============================
    // Sinkronisasi kelompok otomatis
    // ===============================
public function sinkron()
{
    $user = auth()->user();

    // 1. Hanya mahasiswa yang boleh sinkron
    if ($user->role !== 'mahasiswa') {
        return redirect()->back()->with('warning', 'Hanya mahasiswa yang dapat melakukan sinkronisasi kelompok.');
    }

    // 2. Pastikan user punya info kelas & role_kelompok
    if (!$user->role_kelompok || !$user->kelas) {
        return redirect()->back()->with('warning', 'Anda belum tergabung dalam kelompok atau belum memiliki kelas.');
    }

    $roleKelompok = $user->role_kelompok;  // misal: 1, 2, 3
    $kelas        = $user->kelas;          // misal: 3A, 3B, dst.

    // ==========================
    // 3. CARI DATA MAHASISWA DULU (PAKAI EMAIL)
    // ==========================
    $mahasiswa = Mahasiswa::where('email', $user->email)->first();

    // Fallback pakai nim_nip kalau email belum ketemu
    if (!$mahasiswa && !empty($user->nim_nip)) {
        $mahasiswa = Mahasiswa::where('nim', $user->nim_nip)->first();
    }

    if (!$mahasiswa) {
        return redirect()->back()->with(
            'warning',
            'Data mahasiswa dengan email ' . $user->email . ' tidak ditemukan di tabel mahasiswa. Hubungi admin.'
        );
    }

    // ==========================
    // 4. BARU CARI / BUAT KELOMPOKNYA
    // ==========================
    $kelompok = Kelompok::firstOrCreate(
        [
            'nama_kelompok' => 'Kelompok ' . $roleKelompok,
            'kelas'         => $kelas,
        ],
        [
            'kode_mk'      => 'A01K,A02K,A03K,A04K',
            'judul_proyek' => 'Judul Proyek Default',
        ]
    );

    // =======================================
    // 5. CEK: apakah mahasiswa sudah punya kelompok lain?
    // =======================================
    if ($mahasiswa->kelompok_id && $mahasiswa->kelompok_id != $kelompok->id_kelompok) {
        return redirect()->back()->with(
            'warning',
            'Anda sudah terdaftar pada kelompok lain, sehingga tidak dapat disinkron ke kelompok ini.'
        );
    }

    // =======================================
    // 6. MASUKKAN MAHASISWA KE KELOMPOK INI
    // =======================================
    if ($mahasiswa->kelompok_id != $kelompok->id_kelompok) {
        $mahasiswa->kelompok_id = $kelompok->id_kelompok;
        $mahasiswa->save();
    }

    // =======================================
    // 7. JIKA KELOMPOK BELUM PUNYA KETUA → JADIKAN USER INI KETUA
    // =======================================
    $msgTambahan = '';

    if (!$kelompok->ketua_id) {
        $kelompok->ketua_id = $mahasiswa->id;
        $kelompok->save();

        // opsional: update peran di tabel users
        $user->role_di_kelompok = 'Ketua';
        $user->save();

        $msgTambahan = ' Anda ditetapkan sebagai ketua kelompok.';
    } else {
        if ($user->role_di_kelompok !== 'Ketua') {
            $user->role_di_kelompok = 'Anggota';
            $user->save();
        }
    }

    return redirect()->back()->with(
        'success',
        "Sinkronisasi berhasil. Anda telah tergabung dalam {$kelompok->nama_kelompok} kelas {$kelas}.{$msgTambahan}"
    );
}

    // ===============================
    // Daftar kelompok per kelas
    // ===============================
    public function index()
    {
        // Auto-sync for Mahasiswa (Ensure their group exists and they are linked)
        $user = auth()->user();
        if ($user && $user->role === 'mahasiswa' && $user->role_kelompok && $user->kelas) {
            $mahasiswa = Mahasiswa::where('email', $user->email)->first();
            if ($mahasiswa) {
                $namaKelompok = 'Kelompok ' . $user->role_kelompok . ' (' . $user->kelas . ')';
                $kelompok = Kelompok::firstOrCreate(
                    ['nama_kelompok' => $namaKelompok, 'kelas' => $user->kelas],
                    ['kode_mk' => 'PBL-' . $user->kelas, 'judul_proyek' => 'Belum ada judul']
                );

                if ($mahasiswa->kelompok_id != $kelompok->id_kelompok) {
                    $mahasiswa->kelompok_id = $kelompok->id_kelompok;
                    $mahasiswa->save();
                }
                
                if ($user->role_di_kelompok === 'Ketua' && !$kelompok->ketua_id) {
                    $kelompok->update(['ketua_id' => $mahasiswa->id]);
                }
            }
        }

        $kelasList = ['3A', '3B', '3C', '3D', '3E'];
        $kelompokByKelas = [];

        foreach ($kelasList as $kelas) {
            $kelompokByKelas[$kelas] = Kelompok::where('kelas', $kelas)
                ->orderBy('nama_kelompok', 'asc')
                ->get();
        }

        return view('kelompok.index', compact('kelasList', 'kelompokByKelas'));
    }

    // ===============================
    // Tampilkan per kelas
    // ===============================
    public function showByKelas($kelas)
    {
        if (!in_array($kelas, ['3A', '3B', '3C', '3D', '3E'])) abort(404);

        $kelompok = Kelompok::where('kelas', $kelas)
            ->orderBy('nama_kelompok', 'asc')
            ->paginate(10);

        return view('kelompok.byKelas', compact('kelompok', 'kelas'));
    }

    // ===============================
    // Detail kelompok
    // ===============================
    public function show(Kelompok $kelompok)
    {
        $kelompok->load(['mahasiswa', 'ketua']);
        return view('kelompok.show', compact('kelompok'));
    }

    // ===============================
    // Form create – kirim daftar mahasiswa
    // ===============================
    public function create(Request $request)
    {
        // kelas default:
        // ?kelas=...  ATAU  kelas user login  ATAU '3A'
        $kelasDefault = $request->query('kelas', auth()->user()->kelas ?? '3A');

        // mahasiswa sekelas yang belum punya kelompok
        $mahasiswas = Mahasiswa::where('kelas', $kelasDefault)
            ->whereNull('kelompok_id')
            ->orderBy('nama')
            ->get();

        return view('kelompok.create', compact('kelasDefault', 'mahasiswas'));
    }

    // ===============================
    // Simpan kelompok (CREATE)
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'kode_mk'       => 'required|string|max:255',
            'nama_kelompok' => 'required|string|max:255',
            'kelas'         => 'required|in:3A,3B,3C,3D,3E',
            'judul_proyek'  => 'required|string|max:255',

            'mahasiswas'    => 'required|array|min:4',
            'mahasiswas.*'  => 'exists:mahasiswas,id',

            'ketua_id'      => 'required|exists:mahasiswas,id',
        ]);

        if (!in_array($request->ketua_id, $request->mahasiswas)) {
            return back()->withErrors([
                'ketua_id' => 'Ketua harus salah satu dari anggota kelompok.'
            ])->withInput();
        }

        $exists = Kelompok::where('nama_kelompok', $request->nama_kelompok)
            ->where('kelas', $request->kelas)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('warning', 'Nama kelompok sudah dipakai di kelas ini.')
                ->withInput();
        }

        $kelompok = Kelompok::create([
            'kode_mk'       => $request->kode_mk,
            'nama_kelompok' => $request->nama_kelompok,
            'kelas'         => $request->kelas,
            'judul_proyek'  => $request->judul_proyek,
            'ketua_id'      => $request->ketua_id,
        ]);

        Mahasiswa::whereIn('id', $request->mahasiswas)
            ->update(['kelompok_id' => $kelompok->id_kelompok]);

        return redirect()->route('kelompok.byKelas', $request->kelas)
            ->with('success', 'Kelompok dan anggota berhasil dibuat!');
    }

    // ===============================
    // Form edit kelompok + anggota + ketua
    // ===============================
    public function edit(Kelompok $kelompok)
    {
        $kelompok->load(['mahasiswa', 'ketua']);

        $mahasiswas = Mahasiswa::where('kelas', $kelompok->kelas)
            ->orderBy('nama')
            ->get();

        return view('kelompok.edit', compact('kelompok', 'mahasiswas'));
    }

    // ===============================
    // Update kelompok + anggota + ketua
    // ===============================
    public function update(Request $request, Kelompok $kelompok)
    {
        $request->validate([
            'kode_mk'       => 'required|string|max:255',
            'nama_kelompok' => 'required|string|max:255',
            'kelas'         => 'required|in:3A,3B,3C,3D,3E',
            'judul_proyek'  => 'required|string|max:255',

            'mahasiswas'    => 'required|array|min:4',
            'mahasiswas.*'  => 'exists:mahasiswas,id',

            'ketua_id'      => 'required|exists:mahasiswas,id',
        ]);

        if (!in_array($request->ketua_id, $request->mahasiswas)) {
            return back()->withErrors([
                'ketua_id' => 'Ketua harus salah satu dari anggota kelompok.'
            ])->withInput();
        }

        $exists = Kelompok::where('nama_kelompok', $request->nama_kelompok)
            ->where('kelas', $request->kelas)
            ->where('id_kelompok', '!=', $kelompok->id_kelompok)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('warning', 'Nama kelompok sudah digunakan.')
                ->withInput();
        }

        $kelompok->update([
            'kode_mk'       => $request->kode_mk,
            'nama_kelompok' => $request->nama_kelompok,
            'kelas'         => $request->kelas,
            'judul_proyek'  => $request->judul_proyek,
            'ketua_id'      => $request->ketua_id,
        ]);

        Mahasiswa::where('kelompok_id', $kelompok->id_kelompok)
            ->update(['kelompok_id' => null]);

        Mahasiswa::whereIn('id', $request->mahasiswas)
            ->update(['kelompok_id' => $kelompok->id_kelompok]);

        return redirect()->route('kelompok.byKelas', $request->kelas)
            ->with('success', 'Kelompok & anggota berhasil diperbarui!');
    }

    // ===============================
    // Halaman kelola anggota khusus
    // ===============================
    public function manageAnggota(Kelompok $kelompok)
    {
        $restrictedRoles = ['mahasiswa', 'dosen', 'koordinator_prodi', 'koordinator_pbl'];
        if (in_array(auth()->user()->role, $restrictedRoles)) {
            abort(403);
        }

        $kelompok->load(['mahasiswa', 'ketua']);

        $calonMahasiswa = Mahasiswa::where('kelas', $kelompok->kelas)
            ->where(function ($q) use ($kelompok) {
                $q->whereNull('kelompok_id')
                  ->orWhere('kelompok_id', $kelompok->id_kelompok);
            })
            ->orderBy('nama')
            ->get();

        return view('kelompok.manage_anggota', compact('kelompok', 'calonMahasiswa'));
    }

    // ===============================
    // Update anggota + ketua saja
    // ===============================
    public function updateAnggota(Request $request, Kelompok $kelompok)
    {
        $restrictedRoles = ['mahasiswa', 'dosen', 'koordinator_prodi', 'koordinator_pbl'];
        if (in_array(auth()->user()->role, $restrictedRoles)) {
            abort(403);
        }

        $request->validate([
            'mahasiswas'   => 'required|array|min:4',
            'mahasiswas.*' => 'exists:mahasiswas,id',
            'ketua_id'     => 'required|exists:mahasiswas,id',
        ]);

        if (!in_array($request->ketua_id, $request->mahasiswas)) {
            return back()->withErrors([
                'ketua_id' => 'Ketua harus salah satu dari anggota kelompok.'
            ])->withInput();
        }

        $kelompok->update([
            'ketua_id' => $request->ketua_id,
        ]);

        Mahasiswa::where('kelompok_id', $kelompok->id_kelompok)
            ->update(['kelompok_id' => null]);

        Mahasiswa::whereIn('id', $request->mahasiswas)
            ->update(['kelompok_id' => $kelompok->id_kelompok]);

        return redirect()
            ->route('kelompok.show', $kelompok->id_kelompok)
            ->with('success', 'Anggota dan ketua kelompok berhasil diperbarui.');
    }

    // ===============================
    // Hapus kelompok
    // ===============================
    public function destroy(Kelompok $kelompok)
    {
        $kelas = $kelompok->kelas;

        Mahasiswa::where('kelompok_id', $kelompok->id_kelompok)
            ->update(['kelompok_id' => null]);

        $kelompok->delete();

        return redirect()->route('kelompok.byKelas', $kelas)
            ->with('success', 'Kelompok berhasil dihapus!');
    }
}
