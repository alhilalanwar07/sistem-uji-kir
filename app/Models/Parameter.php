<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'deskripsi'];

    public function aturans()
    {
        return $this->hasMany(Aturan::class);
    }
}
