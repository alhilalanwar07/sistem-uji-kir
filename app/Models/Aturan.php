<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aturan extends Model
{
    use HasFactory;

    protected $fillable = ['parameter_id', 'nama_aturan', 'cf_value'];

    public function parameter()
    {
        return $this->belongsTo(Parameter::class);
    }
}
