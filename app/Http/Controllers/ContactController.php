<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        return view('contacts.contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required',
            'email' => 'required|email',
            'pesan' => 'required',
        ]);

        Contact::create($request->all());

        return redirect()->back()->with('success', 'Pesan berhasil dikirim!');
    }
}

