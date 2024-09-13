<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'latitud',
        'longitud',
        'densidad',
        'altura',
        'idAmbiente'
    ];
    public $timestamps = false;
}
