<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SekolahBola extends Model
{
    use HasFactory;

    // Mapping ke tabel database (opsional, kalau nama tabel != plural dari model)
    protected $table = 'sekolah_bolas';

    // Kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'nama',
        'pic',
        'email',
        'telepon',
    ];
    public function pemainBola()
{
   return $this->hasMany(PemainBola::class, 'sekolah_bola_id');
}


    // Relasi: 1 Sekolah punya banyak Pemain
    public function pemainBolas()
    {
       return $this->hasMany(PemainBola::class, 'sekolah_bola_id');
    }
    
}
