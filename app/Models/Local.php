<?php

namespace App\Models;

use App\Enums\TipoHabEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Local extends Model
{
    use HasFactory;
    protected $table = 'locales';

    protected $fillable = [
        'tipoHabitacion',
        'largo',
        'ancho',
        'alto',
        'idAmbiente'
    ];
    protected $casts = [
        'tipoHabitacion' => TipoHabEnum::class,
    ];
    public $timestamps = false;
}
