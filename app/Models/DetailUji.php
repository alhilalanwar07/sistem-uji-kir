<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailUji extends Model
{
    use HasFactory;

    protected $fillable = ['uji_id', 'parameter_id', 'aturan_id', 'cf_value'];

    public function uji()
    {
        return $this->belongsTo(Uji::class);
    }

    public function parameter()
    {
        return $this->belongsTo(Parameter::class);
    }

    public function aturan()
    {
        return $this->belongsTo(Aturan::class);
    }
}
