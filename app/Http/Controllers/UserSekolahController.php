<?php

namespace App\Http\Controllers;

use App\Models\SekolahBola;
use App\Models\PemainBola;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserSekolahController extends Controller
{
    /**
     * Show user management page menggunakan token
     */
    public function show($userToken)
    {
        $sekolah = SekolahBola::byUserToken($userToken)
            ->with('pemainBola')
            ->first();

        if (!$sekolah) {
            abort(404, 'Halaman tidak ditemukan atau token tidak valid');
        }

        // Hitung kuota berdasarkan kategori umur
        $kuotaData = [
            'total' => $sekolah->pemainBola->count(),
            '7-8' => $sekolah->pemainBola->where('umur_kategori', '7-8')->count(),
            '9-10' => $sekolah->pemainBola->where('umur_kategori', '9-10')->count(),
            '11-12' => $sekolah->pemainBola->where('umur_kategori', '11-12')->count(),
        ];

        return view('user.sekolah.show', compact('sekolah', 'kuotaData'));
    }

    /**
     * Update data sekolah
     */
    public function updateSekolah(Request $request, $userToken)
    {
        $sekolah = SekolahBola::byUserToken($userToken)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
            'email' => 'required|email|unique:sekolah_bolas,email,' . $sekolah->id,
            'telepon' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $sekolah->update($request->only(['nama', 'pic', 'email', 'telepon']));

        return response()->json([
            'success' => true,
            'message' => 'Data sekolah berhasil diperbarui',
            'data' => $sekolah->fresh()
        ]);
    }

    /**
     * Update data pemain
     */
    public function updatePemain(Request $request, $userToken, $pemainId)
    {
        $sekolah = SekolahBola::byUserToken($userToken)->firstOrFail();
        $pemain = PemainBola::where('sekolah_bola_id', $sekolah->id)
            ->where('id', $pemainId)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'umur' => 'required|integer|min:7|max:12',
            'umur_kategori' => 'required|in:7-8,9-10,11-12',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validasi konsistensi umur dan kategori
        $this->validateUmurKategori($request->umur, $request->umur_kategori);

        $pemain->update([
            'nama' => $request->nama,
            'umur' => $request->umur,
            'umur_kategori' => $request->umur_kategori,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data pemain berhasil diperbarui',
            'data' => $pemain->fresh()
        ]);
    }

    /**
     * Tambah pemain baru
     */
    public function storePemain(Request $request, $userToken)
    {
        $sekolah = SekolahBola::byUserToken($userToken)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'umur' => 'required|integer|min:7|max:12',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Auto set kategori umur
        $umurKategori = match(true) {
            $request->umur >= 7 && $request->umur <= 8 => '7-8',
            $request->umur >= 9 && $request->umur <= 10 => '9-10',
            $request->umur >= 11 && $request->umur <= 12 => '11-12',
            default => '7-8'
        };

        $pemain = PemainBola::create([
            'nama' => $request->nama,
            'umur' => $request->umur,
            'umur_kategori' => $umurKategori,
            'sekolah_bola_id' => $sekolah->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pemain baru berhasil ditambahkan',
            'data' => $pemain
        ]);
    }

    /**
     * Hapus pemain
     */
    public function deletePemain($userToken, $pemainId)
    {
        $sekolah = SekolahBola::byUserToken($userToken)->firstOrFail();
        $pemain = PemainBola::where('sekolah_bola_id', $sekolah->id)
            ->where('id', $pemainId)
            ->firstOrFail();

        // Cek minimal harus ada 1 pemain
        $totalPemain = PemainBola::where('sekolah_bola_id', $sekolah->id)->count();
        if ($totalPemain <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menghapus pemain. Minimal harus ada 1 pemain.'
            ], 422);
        }

        $pemain->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pemain berhasil dihapus'
        ]);
    }

    /**
     * Validasi konsistensi umur dan kategori
     */
    private function validateUmurKategori($umur, $kategori)
    {
        $valid = false;
        
        switch ($kategori) {
            case '7-8':
                $valid = $umur >= 7 && $umur <= 8;
                break;
            case '9-10':
                $valid = $umur >= 9 && $umur <= 10;
                break;
            case '11-12':
                $valid = $umur >= 11 && $umur <= 12;
                break;
        }

        if (!$valid) {
            throw new \Exception("Kategori umur {$kategori} tidak sesuai dengan umur {$umur} tahun");
        }
    }
}
