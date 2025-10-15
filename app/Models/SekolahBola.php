<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SekolahBola extends Model
{
    use HasFactory;

    protected $table = 'sekolah_bolas';

    protected $fillable = [
        'nama',
        'pic',
        'email',
        'telepon',
        'user_token',
        'jumlah_pemain_7_8',
        'jumlah_pemain_9_10',
        'jumlah_pemain_11_12',
        'jumlah_pemain_total',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // PENTING: Hapus appends untuk menghindari N+1 query
    // Data ini akan di-load lewat eager loading di Resource
    protected $appends = [
        'user_url',
    ];

    /**
     * Boot method untuk generate user_token otomatis
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->user_token)) {
                $model->user_token = self::generateUserToken();
            }
        });
    }

    /**
     * Generate unique user token menggunakan UUID untuk keamanan maksimal
     */
    public static function generateUserToken(): string
    {
        do {
            $token = (string) Str::uuid();
        } while (self::where('user_token', $token)->exists());

        return $token;
    }

    /**
     * Update player counts dari database
     * Dipanggil setelah create/update/delete pemain
     */
    public function updatePlayerCounts(): void
    {
        $counts = $this->pemainBolas()
            ->selectRaw('umur_kategori, COUNT(*) as count')
            ->groupBy('umur_kategori')
            ->pluck('count', 'umur_kategori')
            ->toArray();

        $this->update([
            'jumlah_pemain_7_8' => $counts['7-8'] ?? 0,
            'jumlah_pemain_9_10' => $counts['9-10'] ?? 0,
            'jumlah_pemain_11_12' => $counts['11-12'] ?? 0,
            'jumlah_pemain_total' => array_sum($counts),
        ]);
    }

    /**
     * Relasi dengan PemainBola
     */
    public function pemainBola(): HasMany
    {
        return $this->hasMany(PemainBola::class, 'sekolah_bola_id');
    }

    /**
     * Alias untuk pemainBola (untuk kompatibilitas)
     */
    public function pemainBolas(): HasMany
    {
        return $this->pemainBola();
    }

    /**
     * Relasi dengan KuotaSekolah
     */
    public function kuotaSekolah(): HasOne
    {
        return $this->hasOne(KuotaSekolah::class, 'sekolah_bola_id');
    }

    /**
     * Get user URL
     */
    public function getUserUrlAttribute(): string
    {
        return url('/user/' . $this->user_token);
    }

    /**
     * Get kuota data dalam format array
     * OPTIMIZED: Menggunakan relasi yang sudah di-load
     */
    public function getKuotaDataAttribute(): array
    {
        $kuota = $this->kuotaSekolah;
        
        if (!$kuota) {
            return [
                'total' => 0,
                '7-8' => 0,
                '9-10' => 0,
                '11-12' => 0,
                'has_quota' => false
            ];
        }

        return [
            'total' => $kuota->total_kuota,
            '7-8' => $kuota->kuota_7_8,
            '9-10' => $kuota->kuota_9_10,
            '11-12' => $kuota->kuota_11_12,
            'has_quota' => true,
            'notes' => $kuota->notes,
            'updated_by' => $kuota->updated_by,
            'updated_at' => $kuota->updated_at
        ];
    }

    /**
     * Get current player counts per category
     * OPTIMIZED: Gunakan kolom denormalized atau withCount dari query
     */
    public function getPlayerCountsAttribute(): array
    {
        // Prioritas 1: Gunakan data dari withCount (jika ada)
        if (isset($this->attributes['jumlah_pemain_7_8'])) {
            return [
                '7-8' => (int) $this->attributes['jumlah_pemain_7_8'],
                '9-10' => (int) $this->attributes['jumlah_pemain_9_10'],
                '11-12' => (int) $this->attributes['jumlah_pemain_11_12'],
                'total' => (int) $this->attributes['jumlah_pemain_total']
            ];
        }

        // Prioritas 2: Cache untuk menghindari query berulang
        if (!isset($this->attributes['_player_counts_cache'])) {
            $counts = $this->pemainBolas()
                ->selectRaw('umur_kategori, COUNT(*) as count')
                ->groupBy('umur_kategori')
                ->pluck('count', 'umur_kategori')
                ->toArray();

            $this->attributes['_player_counts_cache'] = [
                '7-8' => $counts['7-8'] ?? 0,
                '9-10' => $counts['9-10'] ?? 0,
                '11-12' => $counts['11-12'] ?? 0,
                'total' => array_sum($counts)
            ];
        }

        return $this->attributes['_player_counts_cache'];
    }

    /**
     * Get current player counts (method version for explicit calls)
     */
    public function getCurrentPlayerCounts(): array
    {
        return $this->player_counts;
    }

    /**
     * Get kuota status menggunakan method dari KuotaSekolah
     * OPTIMIZED: Gunakan relasi yang sudah di-load
     */
    public function getKuotaStatusAttribute(): array
    {
        $kuota = $this->kuotaSekolah;
        
        if (!$kuota) {
            return [
                'status' => 'no_quota',
                'message' => 'Kuota belum diset',
                'color' => 'gray',
                'overall_percentage' => 0,
                'total_kuota' => 0,
                'total_terisi' => $this->jumlah_pemain_total ?? 0,
                'total_sisa' => 0,
                'details' => []
            ];
        }

        $status = $kuota->getKuotaStatus();
        
        // Add color and message based on status
        $statusConfig = [
            'penuh' => ['color' => 'danger', 'message' => 'Kuota Penuh'],
            'hampir_penuh' => ['color' => 'warning', 'message' => 'Hampir Penuh'],
            'sedang' => ['color' => 'info', 'message' => 'Terisi Sedang'],
            'tersedia' => ['color' => 'success', 'message' => 'Masih Tersedia'],
        ];

        $config = $statusConfig[$status['status']] ?? ['color' => 'gray', 'message' => 'Status Tidak Dikenal'];
        
        return array_merge($status, $config);
    }

    /**
     * Check if quota is available for specific category
     */
    public function isKuotaAvailable(string $kategori): bool
    {
        $kuota = $this->kuotaSekolah;
        
        if (!$kuota) {
            return false;
        }

        $playerCounts = $this->getCurrentPlayerCounts();
        return $kuota->isKuotaAvailable($kategori, $playerCounts[$kategori] ?? 0);
    }

    /**
     * Get remaining quota for specific category
     */
    public function getRemainingKuota(string $kategori): int
    {
        $kuota = $this->kuotaSekolah;
        
        if (!$kuota) {
            return 0;
        }

        $playerCounts = $this->getCurrentPlayerCounts();
        return $kuota->getRemainingKuota($kategori, $playerCounts[$kategori] ?? 0);
    }

    /**
     * Get quota summary for all categories
     * OPTIMIZED: Gunakan data dari kolom denormalized
     */
    public function getKuotaSummary(): array
    {
        $kuotaData = $this->kuota_data;
        
        // Gunakan kolom denormalized jika tersedia
        $playerCounts = [
            '7-8' => $this->jumlah_pemain_7_8 ?? 0,
            '9-10' => $this->jumlah_pemain_9_10 ?? 0,
            '11-12' => $this->jumlah_pemain_11_12 ?? 0,
            'total' => $this->jumlah_pemain_total ?? 0
        ];
        
        if (!$kuotaData['has_quota']) {
            return [
                'has_quota' => false,
                'message' => 'Kuota belum diset untuk SSB ini'
            ];
        }

        $summary = [];
        foreach (['7-8', '9-10', '11-12'] as $kategori) {
            $kuotaKategori = $kuotaData[$kategori];
            $pemainKategori = $playerCounts[$kategori];
            $sisa = max(0, $kuotaKategori - $pemainKategori);
            $percentage = $kuotaKategori > 0 ? round(($pemainKategori / $kuotaKategori) * 100, 1) : 0;

            $summary[$kategori] = [
                'kuota' => $kuotaKategori,
                'terisi' => $pemainKategori,
                'sisa' => $sisa,
                'percentage' => $percentage,
                'status' => $this->getCategoryStatus($percentage),
                'is_full' => $pemainKategori >= $kuotaKategori
            ];
        }

        return [
            'has_quota' => true,
            'categories' => $summary,
            'total' => [
                'kuota' => $kuotaData['total'],
                'terisi' => $playerCounts['total'],
                'sisa' => max(0, $kuotaData['total'] - $playerCounts['total']),
                'percentage' => $kuotaData['total'] > 0 ? round(($playerCounts['total'] / $kuotaData['total']) * 100, 1) : 0
            ]
        ];
    }

    /**
     * Get category status based on percentage
     */
    private function getCategoryStatus(float $percentage): string
    {
        if ($percentage >= 100) {
            return 'penuh';
        } elseif ($percentage >= 80) {
            return 'hampir_penuh';
        } elseif ($percentage >= 50) {
            return 'sedang';
        } else {
            return 'tersedia';
        }
    }

    /**
     * Check if any category is full
     */
    public function hasFullCategory(): bool
    {
        if (!$this->kuotaSekolah) {
            return false;
        }

        $playerCounts = [
            '7-8' => $this->jumlah_pemain_7_8 ?? 0,
            '9-10' => $this->jumlah_pemain_9_10 ?? 0,
            '11-12' => $this->jumlah_pemain_11_12 ?? 0,
        ];
        $kuotaData = $this->kuota_data;

        foreach (['7-8', '9-10', '11-12'] as $kategori) {
            if (($playerCounts[$kategori] ?? 0) >= $kuotaData[$kategori]) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if all categories are full
     */
    public function isAllCategoriesFull(): bool
    {
        if (!$this->kuotaSekolah) {
            return false;
        }

        $playerCounts = [
            '7-8' => $this->jumlah_pemain_7_8 ?? 0,
            '9-10' => $this->jumlah_pemain_9_10 ?? 0,
            '11-12' => $this->jumlah_pemain_11_12 ?? 0,
        ];
        $kuotaData = $this->kuota_data;

        foreach (['7-8', '9-10', '11-12'] as $kategori) {
            if (($playerCounts[$kategori] ?? 0) < $kuotaData[$kategori]) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get available categories (categories that still have quota)
     */
    public function getAvailableCategories(): array
    {
        if (!$this->kuotaSekolah) {
            return [];
        }

        $availableCategories = [];
        $playerCounts = [
            '7-8' => $this->jumlah_pemain_7_8 ?? 0,
            '9-10' => $this->jumlah_pemain_9_10 ?? 0,
            '11-12' => $this->jumlah_pemain_11_12 ?? 0,
        ];
        $kuotaData = $this->kuota_data;

        foreach (['7-8', '9-10', '11-12'] as $kategori) {
            if (($playerCounts[$kategori] ?? 0) < $kuotaData[$kategori]) {
                $availableCategories[] = $kategori;
            }
        }

        return $availableCategories;
    }

    /**
     * Scope untuk SSB yang memiliki kuota
     */
    public function scopeWithKuota($query)
    {
        return $query->whereHas('kuotaSekolah');
    }

    /**
     * Scope untuk SSB yang belum memiliki kuota
     */
    public function scopeWithoutKuota($query)
    {
        return $query->whereDoesntHave('kuotaSekolah');
    }

    /**
     * Scope untuk SSB dengan status kuota tertentu
     */
    public function scopeWithKuotaStatus($query, string $status)
    {
        return $query->whereHas('kuotaSekolah', function ($q) use ($status) {
            // This would need to be implemented based on your specific status logic
            // For now, we'll keep it simple
            $q->whereRaw('1=1'); // Placeholder
        });
    }

    /**
     * Scope untuk mencari berdasarkan user_token
     */
    public function scopeByUserToken($query, $token)
    {
        return $query->where('user_token', $token);
    }

    /**
     * Scope untuk load data dengan optimal relations (UNTUK FILAMENT)
     * Menghindari N+1 query problem
     */
    public function scopeWithOptimalRelations($query)
    {
        return $query->with(['kuotaSekolah:id,sekolah_bola_id,kuota_7_8,kuota_9_10,kuota_11_12,notes,updated_by,updated_at'])
            ->select('sekolah_bolas.*'); // Pastikan semua kolom ter-select termasuk jumlah_pemain_*
    }

    /**
     * Refresh player counts cache
     */
    public function refreshPlayerCountsCache(): void
    {
        unset($this->attributes['_player_counts_cache']);
        // This will force recalculation on next access
        $this->player_counts;
    }

    /**
     * Create or update kuota for this SSB
     */
    public function setKuota(array $kuotaData, ?int $updatedBy = null): KuotaSekolah
    {
        $data = array_merge($kuotaData, [
            'updated_by' => $updatedBy ?? auth()->id()
        ]);

        return $this->kuotaSekolah()->updateOrCreate(
            ['sekolah_bola_id' => $this->id],
            $data
        );
    }

    /**
     * Delete kuota for this SSB
     */
    public function deleteKuota(): bool
    {
        if ($this->kuotaSekolah) {
            return $this->kuotaSekolah->delete();
        }

        return false;
    }
}