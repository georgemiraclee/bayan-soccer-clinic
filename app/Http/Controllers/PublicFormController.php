<?php

namespace App\Http\Controllers;

use App\Models\PemainBola;
use App\Models\SekolahBola;
use Illuminate\Http\Request;

class PublicFormController extends Controller
{
    public function index()
    {
        // ambil semua sekolah bola untuk dropdown
        $sekolahBola = SekolahBola::all();

        return view('public-form', compact('sekolahBola'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sekolah_bola_id' => 'required|exists:sekolah_bolas,id',
            'nama' => 'required|string|max:255',
            'umur_kategori' => 'required|in:7-8,9-10,11-12',
        ]);

        PemainBola::create($validated);

        return redirect()->back()->with('success', 'Pendaftaran berhasil disimpan!');
    }
}
