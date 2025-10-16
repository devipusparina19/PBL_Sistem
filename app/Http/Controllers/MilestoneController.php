<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Milestone;

class MilestoneController extends Controller
{
    // =========================
    // MAHASISWA
    // =========================

    // Tampilkan semua milestone mahasiswa berdasarkan kelompok
    public function indexForMember()
    {
        $user = auth()->user();

        $milestones = Milestone::with(['user', 'kelompok'])
            ->where('kelompok_id', $user->kelompok_id)
            ->orderBy('minggu_ke', 'asc')
            ->get();

        return view('milestone.view', compact('milestones', 'user'));
    }

    // Form tambah milestone
    public function create($kelompok_id)
    {
        return view('milestone.create', compact('kelompok_id'));
    }

    // Simpan milestone baru
    public function store(Request $request, $kelompok_id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'minggu_ke' => 'required|integer|min:1',
        ]);

        Milestone::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'minggu_ke' => $request->minggu_ke,
            'kelompok_id' => $kelompok_id,
            'user_id' => auth()->id(),
            'status' => 'menunggu',
        ]);

        return redirect()->route('milestone.view')
            ->with('success', 'Milestone berhasil ditambahkan.');
    }

    // Form edit milestone
    public function edit($id)
    {
        $milestone = Milestone::with(['user', 'kelompok'])->findOrFail($id);
        return view('milestone.edit', compact('milestone'));
    }

    // Update milestone mahasiswa
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'minggu_ke' => 'required|integer|min:1',
        ]);

        $milestone = Milestone::findOrFail($id);

        $milestone->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'minggu_ke' => $request->minggu_ke,
        ]);

        return redirect()->route('milestone.view')
            ->with('success', 'Milestone berhasil diperbarui.');
    }

    // =========================
    // DOSEN (VALIDASI)
    // =========================

    // Daftar milestone menunggu validasi
    public function indexForDosen()
    {
        $milestones = Milestone::with(['user', 'kelompok'])
            ->where('status', 'menunggu')
            ->orderBy('minggu_ke', 'asc')
            ->get();

        return view('milestone.validasi', compact('milestones'));
    }

    // Dosen validasi milestone
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_dosen' => 'nullable|string',
        ]);

        $milestone = Milestone::findOrFail($id);

        $milestone->update([
            'status' => $request->status,
            'catatan_dosen' => $request->catatan_dosen,
        ]);

        return redirect()->route('milestone.validasi')
            ->with('success', 'Milestone berhasil divalidasi.');
    }
}
