<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemateri extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function pemateriAcaras()
    {
        return $this->hasMany(PemateriAcara::class);
    }

}
