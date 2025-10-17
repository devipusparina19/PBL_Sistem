<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Milestone;

class MilestoneController extends Controller
{
    // =========================
    // MAHASISWA
    // =========================

    // Tampilkan semua milestone mahasiswa berdasarkan kelompok login
    public function indexForMember()
    {
        $user = auth()->user();

        // Jika user belum tergabung di kelompok, beri array kosong
        if (!$user->role_kelompok) {
            return view('milestone.view', [
                'milestones' => collect(),
                'user' => $user,
                'warning' => 'Anda belum tergabung dalam kelompok. Silakan hubungi admin atau koordinator PBL.'
            ]);
        }

        // Ambil milestone berdasarkan kelompok
        $milestones = Milestone::with(['user', 'kelompok'])
            ->where('kelompok_id', $user->role_kelompok)
            ->orderBy('minggu_ke', 'asc')
            ->get();

        return view('milestone.view', compact('milestones', 'user'));
    }

    // Form tambah milestone (hanya ketua)
    public function create()
    {
        $user = auth()->user();

        if (!$user->role_kelompok) {
            return redirect()->route('milestone.view')
                ->with('warning', 'Anda belum tergabung dalam kelompok.');
        }

        if (strtolower($user->role_di_kelompok) !== 'ketua') {
            return redirect()->route('milestone.view')
                ->with('warning', 'Hanya ketua kelompok yang dapat menambahkan milestone.');
        }

        return view('milestone.create', ['kelompok_id' => $user->role_kelompok]);
    }

    // Simpan milestone baru (hanya ketua)
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->role_kelompok) {
            return redirect()->route('milestone.view')
                ->with('warning', 'Anda belum tergabung dalam kelompok.');
        }

        if (strtolower($user->role_di_kelompok) !== 'ketua') {
            return redirect()->route('milestone.view')
                ->with('warning', 'Hanya ketua kelompok yang dapat menambahkan milestone.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'minggu_ke' => 'required|integer|min:1',
        ]);

        Milestone::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'minggu_ke' => $request->minggu_ke,
            'kelompok_id' => $user->role_kelompok,
            'user_id' => $user->id,
            'status' => 'menunggu',
        ]);

        return redirect()->route('milestone.view')
            ->with('success', 'Milestone berhasil ditambahkan.');
    }

    // Form edit milestone
    public function edit($id)
    {
        $milestone = Milestone::with(['user', 'kelompok'])->findOrFail($id);
        $user = auth()->user();

        // Mahasiswa hanya bisa edit milestone miliknya
        if ($user->role !== 'dosen' && $user->id != $milestone->user_id) {
            abort(403, 'Anda tidak berhak mengedit milestone ini.');
        }

        return view('milestone.edit', compact('milestone', 'user'));
    }

    // Update milestone
    public function update(Request $request, $id)
    {
        $milestone = Milestone::findOrFail($id);
        $user = auth()->user();

        // Mahasiswa hanya bisa update milestone miliknya
        if ($user->role !== 'dosen' && $user->id != $milestone->user_id) {
            abort(403, 'Anda tidak berhak mengubah milestone ini.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'minggu_ke' => 'required|integer|min:1',
            'status' => 'nullable|in:menunggu,disetujui,ditolak',
            'catatan_dosen' => 'nullable|string',
        ]);

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'minggu_ke' => $request->minggu_ke,
        ];

        // Jika dosen, update status & catatan
        if ($user->role === 'dosen') {
            $data['status'] = $request->status;
            $data['catatan_dosen'] = $request->catatan_dosen;
        }

        $milestone->update($data);

        return redirect()->route('milestone.view')
            ->with('success', 'Milestone berhasil diperbarui.');
    }

    // =========================
    // DOSEN (VALIDASI)
    // =========================

    // Daftar milestone menunggu validasi (untuk dosen)
    public function indexForDosen()
    {
        $milestones = Milestone::with(['user', 'kelompok'])
            ->where('status', 'menunggu')
            ->orderBy('minggu_ke', 'asc')
            ->get();

        return view('milestone.validasi', compact('milestones'));
    }

    // Update status milestone oleh dosen
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
