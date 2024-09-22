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
        return $this->hasOne(Ubicacion::class, "idAmbiente");
    }

    public function ventana()
    {
        return $this->hasOne(Ventana::class, "idAmbiente");
    }

    public function local()
    {
        return $this->hasOne(Local::class, "idAmbiente");
    }

    public function ocupacion()
    {
        return $this->hasOne(Ocupacion::class, "idAmbiente");
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'idAmbiente');
    }
}
