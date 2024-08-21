<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemateriAcara extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function pemateri()
    {
        return $this->belongsTo(Pemateri::class);
    }

    // Relasi many-to-one dengan Acara
    public function acara()
    {
        return $this->belongsTo(Acara::class);
    }

}
