<?php

namespace App\Models;

use App\Enums\CalidadEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventana extends Model
{
    use HasFactory;

    protected $fillable = [
        'largo',
        'alto',
        'corrediza',
        'calidad',
        'idAmbiente'
    ];

    protected $casts = [
        'calidad' => CalidadEnum::class,
    ];

    public $timestamps = false;
}
