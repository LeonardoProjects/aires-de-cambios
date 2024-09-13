<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ambiente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'idUsuario'
    ];

    public function ubicacion()
    {
        return $this->hasOne(Ubicacion::class);
    }

    public function ventana()
    {
        return $this->hasOne(Ventana::class);
    }

    public function local()
    {
        return $this->hasOne(Local::class);
    }

    public function ocupacion()
    {
        return $this->hasOne(Ocupacion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
