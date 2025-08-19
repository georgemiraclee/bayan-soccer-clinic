<?php

namespace App\Http\Controllers;

use App\Models\PemainBola;
use App\Models\SekolahBola;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PublicFormController extends Controller
{
    /**
     * Tampilkan form pendaftaran
     */
    public function index()
    {
        return view('public-form');
    }

    /**
     * Proses pendaftaran sekolah bola dan pemain
     */
    public function store(Request $request)
    {
        // Validasi data sekolah bola
        $validatedSekolah = $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
            'email' => 'required|email|unique:sekolah_bolas,email',
            'telepon' => 'required|string|max:20',
        ]);

        // Validasi data pemain (array)
        $validatedPemain = $request->validate([
            'pemain' => 'required|array|min:1|max:50',
            'pemain.*.nama' => 'required|string|max:255',
            'pemain.*.umur' => 'required|integer|min:7|max:12',
            'pemain.*.umur_kategori' => 'required|in:7-8,9-10,11-12',
        ]);

        // Validasi konsistensi umur dan kategori
        foreach ($validatedPemain['pemain'] as $index => $pemain) {
            $umur = $pemain['umur'];
            $kategori = $pemain['umur_kategori'];
            
            $isValid = false;
            if ($kategori === '7-8' && $umur >= 7 && $umur <= 8) $isValid = true;
            if ($kategori === '9-10' && $umur >= 9 && $umur <= 10) $isValid = true;
            if ($kategori === '11-12' && $umur >= 11 && $umur <= 12) $isValid = true;
            
            if (!$isValid) {
                throw ValidationException::withMessages([
                    "pemain.{$index}.umur_kategori" => "Kategori umur tidak sesuai dengan umur pemain."
                ]);
            }
        }

        try {
            DB::beginTransaction();

            // Simpan sekolah bola
            $sekolahBola = SekolahBola::create([
                'nama' => $validatedSekolah['nama_sekolah'],
                'pic' => $validatedSekolah['pic'],
                'email' => $validatedSekolah['email'],
                'telepon' => $validatedSekolah['telepon'],
            ]);

            // Simpan semua pemain
            $pemainData = [];
            foreach ($validatedPemain['pemain'] as $pemain) {
                $pemainData[] = [
                    'sekolah_bola_id' => $sekolahBola->id,
                    'nama' => $pemain['nama'],
                    'umur' => $pemain['umur'],
                    'umur_kategori' => $pemain['umur_kategori'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            PemainBola::insert($pemainData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran sekolah bola dan pemain berhasil disimpan!',
                'data' => [
                    'sekolah_bola_id' => $sekolahBola->id,
                    'jumlah_pemain' => count($pemainData)
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tampilkan data sekolah bola dan pemain (untuk admin)
     */
    public function showRegistrations()
    {
        $sekolahBolas = SekolahBola::with('pemainBolas')->orderBy('created_at', 'desc')->get();
        
        return view('registrations.index', compact('sekolahBolas'));
    }

    /**
     * Detail pendaftaran sekolah bola
     */
    public function showDetail($id)
    {
        $sekolahBola = SekolahBola::with('pemainBolas')->findOrFail($id);
        
        return view('registrations.detail', compact('sekolahBola'));
    }

    /**
     * Export data ke Excel/CSV (opsional)
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel'); // excel atau csv
        
        $sekolahBolas = SekolahBola::with('pemainBolas')->get();
        
        // Logic untuk export ke Excel/CSV
        // Bisa menggunakan package seperti Laravel Excel
        
        return response()->json([
            'message' => 'Export functionality akan diimplementasikan sesuai kebutuhan'
        ]);
    }

    /**
     * API untuk mendapatkan statistik pendaftaran
     */
    public function getStats()
    {
        $totalSekolah = SekolahBola::count();
        $totalPemain = PemainBola::count();
        
        $pemainPerKategori = PemainBola::select('umur_kategori', DB::raw('count(*) as total'))
            ->groupBy('umur_kategori')
            ->get();

        $sekolahTerbaru = SekolahBola::with('pemainBolas')
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'total_sekolah' => $totalSekolah,
            'total_pemain' => $totalPemain,
            'pemain_per_kategori' => $pemainPerKategori,
            'sekolah_terbaru' => $sekolahTerbaru
        ]);
    }
}