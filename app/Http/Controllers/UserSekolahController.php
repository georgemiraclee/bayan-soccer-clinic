<?php

namespace App\Http\Controllers;

use App\Models\SekolahBola;
use Illuminate\Http\Request;

class UserSekolahController extends Controller
{
    public function show($id)
    {
        $sekolah = SekolahBola::with('pemainBola')->findOrFail($id);

        return view('user.sekolah.show', compact('sekolah'));
    }
}
