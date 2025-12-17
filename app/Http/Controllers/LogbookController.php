<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; // ✅ Tambahan penting buat ambil minggu otomatis

class LogbookController extends Controller
{
    public function index()
    {
        $logbooks = Logbook::latest()->get();
        return view('logbook.index', compact('logbooks'));
    }

    /**
     * Display a listing of logbooks for dosen (view-only mode).
     */
    public function indexForDosen()
    {
        $logbooks = Logbook::latest()->get();
        return view('logbook.dosen_view', compact('logbooks'));
    }

    public function create()
    {
        return view('logbook.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'minggu_ke' => 'required|string', 
            'judul' => 'required|string|max:255',
            'kelompok' => 'required|string|max:255',
            'rincian' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'tanggal' => $request->tanggal,
            'minggu_ke' => $request->minggu_ke,
            'judul' => $request->judul,
            'kelompok' => $request->kelompok,
            'rincian' => $request->rincian,
        ];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('logbook_fotos', 'public');
        }

        try {
            Logbook::create($data);
            return redirect()->route('logbook.index')->with('success', 'Data logbook berhasil ditambahkan!');
        } catch (\Exception $e) {
            \Log::error('Error menyimpan logbook: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan logbook: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $logbook = Logbook::findOrFail($id);
        return view('logbook.edit', compact('logbook'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'minggu_ke' => 'required|string', 
            'judul' => 'required|string|max:255',
            'kelompok' => 'required|string|max:255',
            'rincian' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $logbook = Logbook::findOrFail($id);
        
        $data = [
            'tanggal' => $request->tanggal,
            'minggu_ke' => $request->minggu_ke,
            'judul' => $request->judul,
            'kelompok' => $request->kelompok,
            'rincian' => $request->rincian,
        ];

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($logbook->foto) {
                Storage::disk('public')->delete($logbook->foto);
            }
            $data['foto'] = $request->file('foto')->store('logbook_fotos', 'public');
        }

        try {
            $logbook->update($data);
            return redirect()->route('logbook.index')->with('success', 'Data logbook berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::error('Error update logbook: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal update logbook: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $logbook = Logbook::findOrFail($id);
        if ($logbook->foto) {
            Storage::disk('public')->delete($logbook->foto);
        }
        $logbook->delete();

        return redirect()->route('logbook.index')->with('success', 'Data logbook berhasil dihapus!');
    }
}
