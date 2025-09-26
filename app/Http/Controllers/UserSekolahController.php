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
        $sekolah = SekolahBola::where('user_token', $userToken)->firstOrFail();
        
        // Ambil data kuota
        $kuotaSekolah = $sekolah->kuotaSekolah;
        
        // Hitung pemain per kategori
        $pemainCounts = [
            '7-8' => $sekolah->pemainBolas()->where('umur_kategori', '7-8')->count(),
            '9-10' => $sekolah->pemainBolas()->where('umur_kategori', '9-10')->count(),
            '11-12' => $sekolah->pemainBolas()->where('umur_kategori', '11-12')->count(),
        ];
        
        // Siapkan data kuota
        if ($kuotaSekolah) {
            $kuotaData = [
                'has_quota' => true,
                '7-8' => $kuotaSekolah->kuota_7_8,
                '9-10' => $kuotaSekolah->kuota_9_10,
                '11-12' => $kuotaSekolah->kuota_11_12,
                'total' => $kuotaSekolah->kuota_7_8 + $kuotaSekolah->kuota_9_10 + $kuotaSekolah->kuota_11_12,
                'current_counts' => [
                    '7-8' => $pemainCounts['7-8'],
                    '9-10' => $pemainCounts['9-10'],
                    '11-12' => $pemainCounts['11-12'],
                    'total' => array_sum($pemainCounts),
                ],
                'remaining' => [
                    '7-8' => max(0, $kuotaSekolah->kuota_7_8 - $pemainCounts['7-8']),
                    '9-10' => max(0, $kuotaSekolah->kuota_9_10 - $pemainCounts['9-10']),
                    '11-12' => max(0, $kuotaSekolah->kuota_11_12 - $pemainCounts['11-12']),
                ],
                'percentage' => [
                    '7-8' => $kuotaSekolah->kuota_7_8 > 0 ? round(($pemainCounts['7-8'] / $kuotaSekolah->kuota_7_8) * 100, 1) : 0,
                    '9-10' => $kuotaSekolah->kuota_9_10 > 0 ? round(($pemainCounts['9-10'] / $kuotaSekolah->kuota_9_10) * 100, 1) : 0,
                    '11-12' => $kuotaSekolah->kuota_11_12 > 0 ? round(($pemainCounts['11-12'] / $kuotaSekolah->kuota_11_12) * 100, 1) : 0,
                ],
            ];
        } else {
            $kuotaData = [
                'has_quota' => false,
                '7-8' => 0,
                '9-10' => 0,
                '11-12' => 0,
                'total' => 0,
                'current_counts' => [
                    '7-8' => $pemainCounts['7-8'],
                    '9-10' => $pemainCounts['9-10'],
                    '11-12' => $pemainCounts['11-12'],
                    'total' => array_sum($pemainCounts),
                ],
                'remaining' => [
                    '7-8' => 0,
                    '9-10' => 0,
                    '11-12' => 0,
                ],
                'percentage' => [
                    '7-8' => 0,
                    '9-10' => 0,
                    '11-12' => 0,
                ],
            ];
        }
        
        return view('user.sekolah.show', compact('sekolah', 'kuotaData'));
    }

    /**
     * Show pemain index page
     */
    public function pemainIndex($token)
    {
        $sekolah = SekolahBola::where('user_token', $token)->firstOrFail();
        
        // Get kuota data
        $kuotaData = $sekolah->kuota_data;
        
        // Get current player counts
        $currentCounts = $sekolah->getCurrentPlayerCounts();
        
        // Merge data untuk view
        $kuotaData = array_merge($kuotaData, [
            'current_counts' => $currentCounts,
            'remaining' => [
                '7-8' => max(0, $kuotaData['7-8'] - $currentCounts['7-8']),
                '9-10' => max(0, $kuotaData['9-10'] - $currentCounts['9-10']),
                '11-12' => max(0, $kuotaData['11-12'] - $currentCounts['11-12']),
            ],
            'percentage' => [
                '7-8' => $kuotaData['7-8'] > 0 ? round(($currentCounts['7-8'] / $kuotaData['7-8']) * 100, 1) : 0,
                '9-10' => $kuotaData['9-10'] > 0 ? round(($currentCounts['9-10'] / $kuotaData['9-10']) * 100, 1) : 0,
                '11-12' => $kuotaData['11-12'] > 0 ? round(($currentCounts['11-12'] / $kuotaData['11-12']) * 100, 1) : 0,
            ]
        ]);
        
        return view('user.pemain.index', compact('sekolah', 'kuotaData'));
    }

    /**
     * Get pemain data for edit modal (AJAX)
     */
    public function edit($userToken, $pemainId)
    {
        $sekolah = SekolahBola::where('user_token', $userToken)->firstOrFail();
        $pemain = PemainBola::where('sekolah_bola_id', $sekolah->id)
            ->where('id', $pemainId)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $pemain
        ]);
    }

    /**
     * Update data sekolah
     */
    public function updateSekolah(Request $request, $userToken)
    {
        $sekolah = SekolahBola::where('user_token', $userToken)->firstOrFail();

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
     * Check quota availability for specific category
     */
    private function checkQuotaAvailability($sekolah, $kategori, $excludePemainId = null)
    {
        $kuotaSekolah = $sekolah->kuotaSekolah;
        
        // Check if quota is set
        if (!$kuotaSekolah) {
            return [
                'available' => false,
                'message' => 'Kuota belum ditetapkan oleh admin',
                'code' => 'NO_QUOTA'
            ];
        }

        // Get quota limit for category
        $quotaLimit = match($kategori) {
            '7-8' => $kuotaSekolah->kuota_7_8,
            '9-10' => $kuotaSekolah->kuota_9_10,
            '11-12' => $kuotaSekolah->kuota_11_12,
            default => 0
        };

        // Count current players in category (excluding the one being edited)
        $currentCount = $sekolah->pemainBolas()
            ->where('umur_kategori', $kategori)
            ->when($excludePemainId, function($query, $id) {
                return $query->where('id', '!=', $id);
            })
            ->count();

        // Check if quota is available
        if ($currentCount >= $quotaLimit) {
            return [
                'available' => false,
                'message' => "Kuota kategori {$kategori} tahun sudah penuh ({$currentCount}/{$quotaLimit})",
                'code' => 'QUOTA_FULL'
            ];
        }

        // Check total quota
        $totalCurrent = $sekolah->pemainBolas()
            ->when($excludePemainId, function($query, $id) {
                return $query->where('id', '!=', $id);
            })
            ->count();
        $totalQuota = $kuotaSekolah->kuota_7_8 + $kuotaSekolah->kuota_9_10 + $kuotaSekolah->kuota_11_12;

        if ($totalCurrent >= $totalQuota) {
            return [
                'available' => false,
                'message' => 'Kuota total SSB sudah terpenuhi',
                'code' => 'TOTAL_QUOTA_FULL'
            ];
        }

        return [
            'available' => true,
            'message' => "Sisa kuota kategori {$kategori}: " . ($quotaLimit - $currentCount) . " pemain",
            'remaining' => $quotaLimit - $currentCount
        ];
    }

    /**
     * Update data pemain (AJAX) with quota validation
     */
    public function updatePemain(Request $request, $userToken, $pemainId)
    {
        $sekolah = SekolahBola::where('user_token', $userToken)->firstOrFail();
        $pemain = PemainBola::where('sekolah_bola_id', $sekolah->id)
            ->where('id', $pemainId)
            ->firstOrFail();

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

        // Check if kategori is changing and validate quota
        if ($pemain->umur_kategori !== $umurKategori) {
            $quotaCheck = $this->checkQuotaAvailability($sekolah, $umurKategori, $pemainId);
            if (!$quotaCheck['available']) {
                return response()->json([
                    'success' => false,
                    'message' => $quotaCheck['message']
                ], 422);
            }
        }

        $pemain->update([
            'nama' => $request->nama,
            'umur' => $request->umur,
            'umur_kategori' => $umurKategori,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data pemain berhasil diperbarui',
            'data' => $pemain->fresh()
        ]);
    }

    /**
     * Tambah pemain baru (AJAX) with enhanced quota validation
     */
    public function storePemain(Request $request, $userToken)
    {
        $sekolah = SekolahBola::where('user_token', $userToken)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'umur' => 'required|integer|min:7|max:12',
            'umur_kategori' => 'required|in:7-8,9-10,11-12'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate umur matches kategori
        $umur = $request->umur;
        $kategori = $request->umur_kategori;
        
        $validKategori = match(true) {
            $umur >= 7 && $umur <= 8 => '7-8',
            $umur >= 9 && $umur <= 10 => '9-10',
            $umur >= 11 && $umur <= 12 => '11-12',
            default => null
        };

        if ($kategori !== $validKategori) {
            return response()->json([
                'success' => false,
                'message' => "Kategori umur {$kategori} tidak sesuai dengan umur {$umur} tahun"
            ], 422);
        }

        // Check quota availability
        $quotaCheck = $this->checkQuotaAvailability($sekolah, $kategori);
        if (!$quotaCheck['available']) {
            return response()->json([
                'success' => false,
                'message' => $quotaCheck['message'],
                'quota_code' => $quotaCheck['code'] ?? null
            ], 422);
        }

        // Check for duplicate names within the same school
        $existingPlayer = PemainBola::where('sekolah_bola_id', $sekolah->id)
            ->where('nama', $request->nama)
            ->first();

        if ($existingPlayer) {
            return response()->json([
                'success' => false,
                'message' => 'Pemain dengan nama tersebut sudah terdaftar di SSB ini'
            ], 422);
        }

        try {
            $pemain = PemainBola::create([
                'nama' => $request->nama,
                'umur' => $request->umur,
                'umur_kategori' => $kategori,
                'sekolah_bola_id' => $sekolah->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Pemain {$pemain->nama} berhasil ditambahkan ke kategori {$kategori} tahun",
                'data' => $pemain,
                'quota_remaining' => $quotaCheck['remaining'] - 1
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan pemain. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Hapus pemain (AJAX)
     */
    public function deletePemain($userToken, $pemainId)
    {
        $sekolah = SekolahBola::where('user_token', $userToken)->firstOrFail();
        $pemain = PemainBola::where('sekolah_bola_id', $sekolah->id)
            ->where('id', $pemainId)
            ->firstOrFail();

        // Optional: Check minimal players requirement
        // Uncomment if you want to enforce minimum players
        /*
        $totalPemain = PemainBola::where('sekolah_bola_id', $sekolah->id)->count();
        if ($totalPemain <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menghapus pemain. Minimal harus ada 1 pemain.'
            ], 422);
        }
        */

        $pemainNama = $pemain->nama;
        $kategori = $pemain->umur_kategori;
        
        try {
            $pemain->delete();

            return response()->json([
                'success' => true,
                'message' => "Pemain {$pemainNama} berhasil dihapus dari kategori {$kategori} tahun"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pemain. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Get quota status for AJAX requests
     */
    public function getQuotaStatus($userToken)
    {
        $sekolah = SekolahBola::where('user_token', $userToken)->firstOrFail();
        $kuotaSekolah = $sekolah->kuotaSekolah;
        
        // Hitung pemain per kategori
        $pemainCounts = [
            '7-8' => $sekolah->pemainBolas()->where('umur_kategori', '7-8')->count(),
            '9-10' => $sekolah->pemainBolas()->where('umur_kategori', '9-10')->count(),
            '11-12' => $sekolah->pemainBolas()->where('umur_kategori', '11-12')->count(),
        ];
        
        if ($kuotaSekolah) {
            $quotaData = [
                'has_quota' => true,
                '7-8' => $kuotaSekolah->kuota_7_8,
                '9-10' => $kuotaSekolah->kuota_9_10,
                '11-12' => $kuotaSekolah->kuota_11_12,
                'total' => $kuotaSekolah->kuota_7_8 + $kuotaSekolah->kuota_9_10 + $kuotaSekolah->kuota_11_12,
                'current_counts' => [
                    '7-8' => $pemainCounts['7-8'],
                    '9-10' => $pemainCounts['9-10'],
                    '11-12' => $pemainCounts['11-12'],
                    'total' => array_sum($pemainCounts),
                ],
                'remaining' => [
                    '7-8' => max(0, $kuotaSekolah->kuota_7_8 - $pemainCounts['7-8']),
                    '9-10' => max(0, $kuotaSekolah->kuota_9_10 - $pemainCounts['9-10']),
                    '11-12' => max(0, $kuotaSekolah->kuota_11_12 - $pemainCounts['11-12']),
                ],
                'can_add' => array_sum($pemainCounts) < ($kuotaSekolah->kuota_7_8 + $kuotaSekolah->kuota_9_10 + $kuotaSekolah->kuota_11_12)
            ];
        } else {
            $quotaData = [
                'has_quota' => false,
                'can_add' => false,
                'message' => 'Kuota belum ditetapkan oleh admin'
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $quotaData
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