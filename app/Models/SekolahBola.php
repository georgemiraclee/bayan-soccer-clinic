<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
    public static function generateUserToken()
    {
        do {
            $token = (string) Str::uuid();
        } while (self::where('user_token', $token)->exists());

        return $token;
    }

    public function pemainBola()
    {
        return $this->hasMany(PemainBola::class, 'sekolah_bola_id');
    }

    public function pemainBolas()
    {
        return $this->hasMany(PemainBola::class, 'sekolah_bola_id');
    }

    /**
     * Get user URL
     */
    public function getUserUrlAttribute()
    {
        return url('/user/' . $this->user_token);
    }

    /**
     * Scope untuk mencari berdasarkan user_token
     */
    public function scopeByUserToken($query, $token)
    {
        return $query->where('user_token', $token);
    }
}