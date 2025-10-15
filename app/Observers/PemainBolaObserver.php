<?php

namespace App\Observers;

use App\Models\PemainBola;

class PemainBolaObserver
{
    /**
     * Handle the PemainBola "created" event.
     */
    public function created(PemainBola $pemainBola): void
    {
        $this->updateSekolahPlayerCounts($pemainBola);
    }

    /**
     * Handle the PemainBola "updated" event.
     */
    public function updated(PemainBola $pemainBola): void
    {
        // Jika umur_kategori atau sekolah_bola_id berubah
        if ($pemainBola->isDirty(['umur_kategori', 'sekolah_bola_id'])) {
            // Update sekolah lama jika pindah sekolah
            if ($pemainBola->isDirty('sekolah_bola_id')) {
                $oldSekolahId = $pemainBola->getOriginal('sekolah_bola_id');
                if ($oldSekolahId) {
                    $oldSekolah = \App\Models\SekolahBola::find($oldSekolahId);
                    if ($oldSekolah) {
                        $oldSekolah->updatePlayerCounts();
                    }
                }
            }
            
            // Update sekolah baru
            $this->updateSekolahPlayerCounts($pemainBola);
        }
    }

    /**
     * Handle the PemainBola "deleted" event.
     */
    public function deleted(PemainBola $pemainBola): void
    {
        $this->updateSekolahPlayerCounts($pemainBola);
    }

    /**
     * Handle the PemainBola "restored" event.
     */
    public function restored(PemainBola $pemainBola): void
    {
        $this->updateSekolahPlayerCounts($pemainBola);
    }

    /**
     * Handle the PemainBola "force deleted" event.
     */
    public function forceDeleted(PemainBola $pemainBola): void
    {
        $this->updateSekolahPlayerCounts($pemainBola);
    }

    /**
     * Update player counts untuk sekolah bola terkait
     */
    private function updateSekolahPlayerCounts(PemainBola $pemainBola): void
    {
        if ($pemainBola->sekolahBola) {
            $pemainBola->sekolahBola->updatePlayerCounts();
        }
    }
}