<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KuotaSekolah extends Model
{
    use HasFactory;

    protected $table = 'kuota_sekolahs';

    protected $fillable = [
        'sekolah_bola_id',
        'kuota_7_8',
        'kuota_9_10',
        'kuota_11_12',
        'updated_by',
        'notes',
    ];

    protected $casts = [
        'kuota_7_8' => 'integer',
        'kuota_9_10' => 'integer',
        'kuota_11_12' => 'integer',
        'updated_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'total_kuota',
        'status_color'
    ];

    /**
     * Relationship dengan SekolahBola
     */
    public function sekolahBola(): BelongsTo
    {
        return $this->belongsTo(SekolahBola::class);
    }

    /**
     * Relationship dengan User (admin yang mengupdate)
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get total kuota
     */
    public function getTotalKuotaAttribute(): int
    {
        return $this->kuota_7_8 + $this->kuota_9_10 + $this->kuota_11_12;
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        $status = $this->getKuotaStatus();
        
        return match($status['status']) {
            'penuh' => 'danger',
            'hampir_penuh' => 'warning',
            'sedang' => 'info',
            'tersedia' => 'success',
            default => 'gray'
        };
    }

    /**
     * Check if kuota is available for specific category
     */
    public function isKuotaAvailable(string $kategori, int $currentCount = 0): bool
    {
        $kuotaField = 'kuota_' . str_replace('-', '_', $kategori);
        
        if (!property_exists($this, $kuotaField)) {
            return false;
        }
        
        return $this->$kuotaField > $currentCount;
    }

    /**
     * Get remaining quota for specific category
     */
    public function getRemainingKuota(string $kategori, int $currentCount = 0): int
    {
        $kuotaField = 'kuota_' . str_replace('-', '_', $kategori);
        
        if (!property_exists($this, $kuotaField)) {
            return 0;
        }
        
        return max(0, $this->$kuotaField - $currentCount);
    }

    /**
     * Get kuota value for specific category
     */
    public function getKuotaByKategori(string $kategori): int
    {
        return match($kategori) {
            '7-8' => $this->kuota_7_8,
            '9-10' => $this->kuota_9_10,
            '11-12' => $this->kuota_11_12,
            default => 0
        };
    }

    /**
     * Get kuota status with detailed breakdown
     */
    public function getKuotaStatus(): array
    {
        $sekolah = $this->sekolahBola;
        
        if (!$sekolah) {
            return [
                'status' => 'unknown',
                'details' => []
            ];
        }

        // Count pemain per kategori
        $pemainCounts = $sekolah->pemainBolas()
            ->selectRaw('umur_kategori, COUNT(*) as count')
            ->groupBy('umur_kategori')
            ->pluck('count', 'umur_kategori')
            ->toArray();

        $details = [
            '7-8' => [
                'kuota' => $this->kuota_7_8,
                'terisi' => $pemainCounts['7-8'] ?? 0,
                'sisa' => max(0, $this->kuota_7_8 - ($pemainCounts['7-8'] ?? 0)),
                'percentage' => $this->kuota_7_8 > 0 ? round((($pemainCounts['7-8'] ?? 0) / $this->kuota_7_8) * 100, 1) : 0,
                'is_full' => ($pemainCounts['7-8'] ?? 0) >= $this->kuota_7_8
            ],
            '9-10' => [
                'kuota' => $this->kuota_9_10,
                'terisi' => $pemainCounts['9-10'] ?? 0,
                'sisa' => max(0, $this->kuota_9_10 - ($pemainCounts['9-10'] ?? 0)),
                'percentage' => $this->kuota_9_10 > 0 ? round((($pemainCounts['9-10'] ?? 0) / $this->kuota_9_10) * 100, 1) : 0,
                'is_full' => ($pemainCounts['9-10'] ?? 0) >= $this->kuota_9_10
            ],
            '11-12' => [
                'kuota' => $this->kuota_11_12,
                'terisi' => $pemainCounts['11-12'] ?? 0,
                'sisa' => max(0, $this->kuota_11_12 - ($pemainCounts['11-12'] ?? 0)),
                'percentage' => $this->kuota_11_12 > 0 ? round((($pemainCounts['11-12'] ?? 0) / $this->kuota_11_12) * 100, 1) : 0,
                'is_full' => ($pemainCounts['11-12'] ?? 0) >= $this->kuota_11_12
            ]
        ];

        $totalKuota = $this->total_kuota;
        $totalTerisi = array_sum(array_column($details, 'terisi'));
        $overallPercentage = $totalKuota > 0 ? round(($totalTerisi / $totalKuota) * 100, 1) : 0;

        // Determine overall status
        if ($overallPercentage >= 100) {
            $status = 'penuh';
        } elseif ($overallPercentage >= 80) {
            $status = 'hampir_penuh';
        } elseif ($overallPercentage >= 50) {
            $status = 'sedang';
        } else {
            $status = 'tersedia';
        }

        return [
            'status' => $status,
            'overall_percentage' => $overallPercentage,
            'total_kuota' => $totalKuota,
            'total_terisi' => $totalTerisi,
            'total_sisa' => max(0, $totalKuota - $totalTerisi),
            'details' => $details,
            'has_full_category' => collect($details)->contains('is_full', true),
            'all_categories_full' => collect($details)->every('is_full', true)
        ];
    }

    /**
     * Get percentage for specific category
     */
    public function getPersentaseKategori(string $kategori): float
    {
        $kuotaKategori = $this->getKuotaByKategori($kategori);
        
        if ($kuotaKategori == 0) {
            return 0;
        }

        $jumlahPemain = $this->sekolahBola->getCurrentPlayerCounts()[$kategori] ?? 0;
        return round(($jumlahPemain / $kuotaKategori) * 100, 1);
    }

    /**
     * Check if specific category is full
     */
    public function isKategoriPenuh(string $kategori): bool
    {
        $kuotaKategori = $this->getKuotaByKategori($kategori);
        $jumlahPemain = $this->sekolahBola->getCurrentPlayerCounts()[$kategori] ?? 0;
        
        return $jumlahPemain >= $kuotaKategori;
    }

    /**
     * Get available categories (not full)
     */
    public function getAvailableCategories(): array
    {
        $availableCategories = [];
        
        foreach (['7-8', '9-10', '11-12'] as $kategori) {
            if (!$this->isKategoriPenuh($kategori)) {
                $availableCategories[] = $kategori;
            }
        }
        
        return $availableCategories;
    }

    /**
     * Get status message for UI
     */
    public function getStatusMessage(): string
    {
        $status = $this->getKuotaStatus();
        
        return match($status['status']) {
            'penuh' => 'Kuota Penuh',
            'hampir_penuh' => 'Hampir Penuh',
            'sedang' => 'Terisi Sedang',
            'tersedia' => 'Masih Tersedia',
            default => 'Status Tidak Dikenal'
        };
    }

    /**
     * Scope untuk kuota dengan status tertentu
     */
    public function scopeWithStatus($query, string $status)
    {
        // This is a complex scope that would need raw SQL
        // For now, we'll implement basic filtering
        return $query->whereHas('sekolahBola');
    }

    /**
     * Scope untuk kuota yang masih tersedia
     */
    public function scopeAvailable($query)
    {
        return $query->whereRaw('
            (kuota_7_8 + kuota_9_10 + kuota_11_12) > 
            (SELECT COUNT(*) FROM pemain_bolas WHERE sekolah_bola_id = kuota_sekolahs.sekolah_bola_id)
        ');
    }

    /**
     * Scope untuk kuota yang sudah penuh
     */
    public function scopeFull($query)
    {
        return $query->whereRaw('
            (kuota_7_8 + kuota_9_10 + kuota_11_12) <= 
            (SELECT COUNT(*) FROM pemain_bolas WHERE sekolah_bola_id = kuota_sekolahs.sekolah_bola_id)
        ');
    }

    /**
     * Boot method untuk auto set updated_by
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($kuotaSekolah) {
            if (auth()->check() && empty($kuotaSekolah->updated_by)) {
                $kuotaSekolah->updated_by = auth()->id();
            }
        });

        // Auto refresh SekolahBola player counts cache when kuota changes
        static::saved(function ($kuotaSekolah) {
            if ($kuotaSekolah->sekolahBola) {
                $kuotaSekolah->sekolahBola->refreshPlayerCountsCache();
            }
        });

        static::deleted(function ($kuotaSekolah) {
            if ($kuotaSekolah->sekolahBola) {
                $kuotaSekolah->sekolahBola->refreshPlayerCountsCache();
            }
        });
    }
}