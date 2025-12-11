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

    public function create()
    {
        return view('logbook.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'minggu_ke' => 'nullable|string', 
            'judul' => 'required|string|max:255',
            'rincian' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        // Otomatis isi nama kelompok dari data mahasiswa login
        if (auth()->check() && auth()->user()->role === 'mahasiswa') {
            $mahasiswa = auth()->user()->mahasiswa;
            if ($mahasiswa && $mahasiswa->kelompok) {
                $data['kelompok'] = $mahasiswa->kelompok->nama_kelompok;
            } else {
                $data['kelompok'] = 'Individu / Belum Ada Kelompok';
            }
        } else {
             $data['kelompok'] = $request->kelompok ?? '-';
        }

        // Otomatis isi minggu_ke jika kosong
        if (empty($data['minggu_ke'])) {
            $today = Carbon::now();
            $data['minggu_ke'] = 'Minggu ke-' . ceil($today->day / 7);
        }

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('logbook_fotos', 'public');
        }

        Logbook::create($data);

        return redirect()->route('logbook.index')->with('success', 'Data logbook berhasil ditambahkan!');
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
