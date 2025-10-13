<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Tampilkan halaman informasi kontak.
     * Tidak ada form input, hanya menampilkan data kontak statis.
     */
    public function index()
    {
        return view('contact'); // Pastikan file ada di resources/views/contact.blade.php
    }
}
