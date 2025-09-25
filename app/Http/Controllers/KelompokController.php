<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    public function index()
{
    $kelompok = Kelompok::paginate(5); // contoh: 5 data per halaman
    return view('kelompok.index', compact('kelompok'));
}

    }

