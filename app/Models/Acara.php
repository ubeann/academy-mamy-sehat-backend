<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acara extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_acara',
        'pemateri',
        'deskripsi',
        'gambar',
        'tgl_mulai',
        'tgl_akhir',
        'tgl_ditutup',
        'lokasi',
        'status',
        'harga_early',
        'harga_reguler',
        'harga_spesialis',
        'tgl_early',
        'wa_link'
    ];

    public function activate()
    {
        $this->status = 'aktif';
        $this->save();
    }

    /**
     * Set the status of the acara to inactive.
     */
    public function deactivate()
    {
        $this->status = 'tidak_aktif';
        $this->save();
    }
    public function materis()
    {
        return $this->hasMany(ListMateri::class);
    }

    // Definisikan relasi ke Fasilitas
    public function fasilitas()
    {
        return $this->hasMany(Fasilitas::class);
    }
    public function pemateriAcara()
    {
        return $this->hasMany(PemateriAcara::class);
    }
    public function pendaftars()
    {
        return $this->hasMany(Pendaftar::class, 'id_acara');
    }



}
