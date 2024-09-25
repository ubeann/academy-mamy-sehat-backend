<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'acara_id',
        'jumlah_bayar',
    'status',
        'external_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function acara()
    {
        return $this->belongsTo(Acara::class);
    }
}
