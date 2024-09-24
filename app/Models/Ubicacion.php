<?php

namespace App\Models;

use App\Enums\AlturaEnum;
use App\Enums\DensidadEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory;
    protected $table = 'ubicaciones';

    protected $fillable = [
        'latitud',
        'longitud',
        'densidad',
        'altura',
        'idAmbiente'
    ];

    protected $casts = [
        'altura' => AlturaEnum::class,
        'densidad' => DensidadEnum::class
    ];

    public $timestamps = false;
}
