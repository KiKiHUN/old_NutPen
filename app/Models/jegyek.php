<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jegyek extends Model
{
    use HasFactory;
    public function ertekelesek()
    {
        return $this->hasMany(Ertekeles::class);
    }
}