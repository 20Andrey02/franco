<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stand extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'platillo', 'descripcion', 'encargado'];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
