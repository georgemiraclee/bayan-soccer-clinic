<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemainBola extends Model
{
    use HasFactory;

    protected $table = 'pemain_bolas';

    protected $fillable = [
        'sekolah_bola_id',
        'nama',
        'umur_kategori',
    ];
    public function sekolah()
{
    return $this->belongsTo(SekolahBola::class);
}

    // Relasi: Pemain milik 1 Sekolah
    public function sekolahBola()
    {
        return $this->belongsTo(SekolahBola::class);
    }
    
}
