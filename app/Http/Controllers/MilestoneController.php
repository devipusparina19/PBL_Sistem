<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Milestone;

class MilestoneController extends Controller
{
    // =========================
    // MEMBER / MAHASISWA
    // =========================

    // Tampilkan milestone untuk mahasiswa sesuai kelompok
    public function indexForMember()
    {
        $user = auth()->user();
        
        $milestones = Milestone::where('kelompok_id', $user->kelompok_id)
                                ->orderBy('minggu_ke', 'asc')
                                ->get();

        return view('milestone.view', compact('milestones', 'user'));
    }

    // Form input milestone baru
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
            'kelompok_id' => $kelompok_id,
            'user_id' => auth()->id(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'minggu_ke' => $request->minggu_ke,
            'status' => 'menunggu', // default status
        ]);

        return redirect()->route('milestone.view')->with('success', 'Milestone berhasil ditambahkan.');
    }

    // Edit milestone
    public function edit($id)
    {
        $milestone = Milestone::findOrFail($id);
        return view('milestone.edit', compact('milestone'));
    }

    // Update milestone
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'minggu_ke' => 'required|integer|min:1',
        ]);

        $milestone = Milestone::findOrFail($id);
        $milestone->update($request->only('judul', 'deskripsi', 'minggu_ke'));

        return redirect()->route('milestone.view')->with('success', 'Milestone berhasil diupdate.');
    }

    // =========================
    // DOSEN / VALIDASI
    // =========================

    // Tampilkan milestone yang menunggu validasi
    public function indexForDosen()
    {
        $milestones = Milestone::where('status', 'menunggu')
                                ->with('user', 'kelompok')
                                ->orderBy('minggu_ke', 'asc')
                                ->get();

        return view('milestone.validasi', compact('milestones'));
    }

    // Update status milestone oleh dosen
    public function updateStatus(Request $request, $id)
    {
        $milestone = Milestone::findOrFail($id);

        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_dosen' => 'nullable|string',
        ]);

        $milestone->update([
            'status' => $request->status,
            'catatan_dosen' => $request->catatan_dosen,
        ]);

        return redirect()->route('milestone.validasi')->with('success', 'Milestone berhasil divalidasi.');
    }
}
