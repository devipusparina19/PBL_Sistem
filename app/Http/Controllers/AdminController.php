<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function manajemenAkun()
    {
        $users = User::orderBy('role')->get();
        return view('admin.manajemen_akun', compact('users'));
    }
}
