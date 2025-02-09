<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uji extends Model
{
    use HasFactory;

    protected $fillable = ['kendaraan_id', 'hasil_cf', 'status'];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function details()
    {
        return $this->hasMany(DetailUji::class);
    }
}
