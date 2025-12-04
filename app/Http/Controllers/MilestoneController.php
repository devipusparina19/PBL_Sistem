<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Milestone;
use App\Models\Kelompok;
use App\Models\Mahasiswa;

class MilestoneController extends Controller
{
    // ============================================
    // HELPER: Ambil Mahasiswa & Kelompok dari user login
    // ============================================
    protected function getMahasiswaDanKelompokDariUser($user)
    {
        // Cari mahasiswa berdasarkan email user
        $mahasiswa = Mahasiswa::where('email', $user->email)->first();

        // Fallback: kalau belum ketemu, pakai nim_nip
        if (!$mahasiswa && !empty($user->nim_nip)) {
            $mahasiswa = Mahasiswa::where('nim', $user->nim_nip)->first();
        }

        if (!$mahasiswa) {
            return [null, null, 'Data mahasiswa Anda tidak ditemukan. Hubungi admin.'];
        }

        if (!$mahasiswa->kelompok_id) {
            return [$mahasiswa, null, 'Anda belum tergabung dalam kelompok.'];
        }

        $kelompok = Kelompok::where('id_kelompok', $mahasiswa->kelompok_id)->first();

        if (!$kelompok) {
            return [$mahasiswa, null, 'Data kelompok Anda tidak ditemukan.'];
        }

        return [$mahasiswa, $kelompok, null];
    }

    // ============================================
    // MAHASISWA
    // ============================================

    // Tampilkan semua milestone untuk anggota kelompok
    public function indexForMember()
    {
        $user = auth()->user();

        [$mahasiswa, $kelompok, $error] = $this->getMahasiswaDanKelompokDariUser($user);

        // Kalau ada error (belum punya data mahasiswa / kelompok)
        if ($error) {
            return view('milestone.view', [
                'milestones' => collect(),
                'user'       => $user,
                'warning'    => $error,
                'isKetua'    => false,
            ]);
        }

        // Ambil semua milestone milik kelompok ini
        $milestones = Milestone::with(['user', 'kelompok'])
            ->where('kelompok_id', $kelompok->id_kelompok)
            ->filterByKelas($kelompok->kelas)   // pakai scope di model Milestone
            ->orderBy('minggu_ke', 'asc')
            ->get();

        // Cek apakah mahasiswa ini adalah ketua
        $isKetua = ($kelompok->ketua_id == $mahasiswa->id);

        // Ambil warning dari session (misal: "Hanya ketua yang boleh...")
        $warning = session('warning');

        return view('milestone.view', compact('milestones', 'user', 'isKetua', 'warning'));
    }

    // Form tambah milestone (HANYA ketua)
    public function create()
    {
        $user = auth()->user();

        [$mahasiswa, $kelompok, $error] = $this->getMahasiswaDanKelompokDariUser($user);

        if ($error) {
            return redirect()->route('milestone.view')->with('warning', $error);
        }

        // Pastikan user ini adalah KETUA kelompok (berdasarkan tabel kelompok)
        if ($kelompok->ketua_id != $mahasiswa->id) {
            return redirect()->route('milestone.view')
                ->with('warning', 'Hanya ketua kelompok yang dapat menambahkan milestone.');
        }

        return view('milestone.create', [
            'kelompok'    => $kelompok,
            'kelompok_id' => $kelompok->id_kelompok,
        ]);
    }

    // Simpan milestone baru
    public function store(Request $request)
    {
        $user = auth()->user();

        [$mahasiswa, $kelompok, $error] = $this->getMahasiswaDanKelompokDariUser($user);

        if ($error) {
            return redirect()->route('milestone.view')->with('warning', $error);
        }

        // Pastikan user ini adalah KETUA kelompok
        if ($kelompok->ketua_id != $mahasiswa->id) {
            return redirect()->route('milestone.view')
                ->with('warning', 'Hanya ketua kelompok yang dapat menambahkan milestone.');
        }

        $request->validate([
            'judul'      => 'required|string|max:255',
            'deskripsi'  => 'required|string',
            'minggu_ke'  => 'required|integer|min:1',
        ]);

        Milestone::create([
            'judul'        => $request->judul,
            'deskripsi'    => $request->deskripsi,
            'minggu_ke'    => $request->minggu_ke,
            'kelompok_id'  => $kelompok->id_kelompok,
            'user_id'      => $user->id,
            'status'       => 'menunggu',
        ]);

        return redirect()->route('milestone.view')
            ->with('success', 'Milestone berhasil ditambahkan.');
    }

    // Form edit milestone (pembuat atau dosen)
    public function edit($id)
    {
        $milestone = Milestone::with(['user', 'kelompok'])->findOrFail($id);
        $user = auth()->user();

        // Dosen boleh, atau pemilik milestone (user_id)
        if ($user->role !== 'dosen' && $user->id != $milestone->user_id) {
            abort(403, 'Anda tidak berhak mengedit milestone ini.');
        }

        return view('milestone.edit', compact('milestone', 'user'));
    }

    // Update milestone
    public function update(Request $request, $id)
    {
        $milestone = Milestone::findOrFail($id);
        $user      = auth()->user();

        if ($user->role !== 'dosen' && $user->id != $milestone->user_id) {
            abort(403, 'Anda tidak berhak mengubah milestone ini.');
        }

        $request->validate([
            'judul'         => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'minggu_ke'     => 'required|integer|min:1',
            'status'        => 'nullable|in:menunggu,disetujui,ditolak',
            'catatan_dosen' => 'nullable|string',
        ]);

        $data = [
            'judul'      => $request->judul,
            'deskripsi'  => $request->deskripsi,
            'minggu_ke'  => $request->minggu_ke,
        ];

        // Dosen boleh mengubah status & catatan
        if ($user->role === 'dosen') {
            $data['status']        = $request->status;
            $data['catatan_dosen'] = $request->catatan_dosen;
        }

        $milestone->update($data);

        return redirect()->route('milestone.view')
            ->with('success', 'Milestone berhasil diperbarui.');
    }

    // ============================================
    // DOSEN (VALIDASI)
    // ============================================

    public function indexForDosen()
    {
        $milestones = Milestone::with(['user', 'kelompok'])
            ->where('status', 'menunggu')
            ->orderBy('minggu_ke', 'asc')
            ->get();

        return view('milestone.validasi', compact('milestones'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'        => 'required|in:disetujui,ditolak',
            'catatan_dosen' => 'nullable|string',
        ]);

        $milestone = Milestone::findOrFail($id);

        $milestone->update([
            'status'        => $request->status,
            'catatan_dosen' => $request->catatan_dosen,
        ]);

        return redirect()->route('milestone.validasi')
            ->with('success', 'Milestone berhasil divalidasi.');
    }
}
