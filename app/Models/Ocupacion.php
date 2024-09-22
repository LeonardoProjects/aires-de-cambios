<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ocupacion extends Model
{
    use HasFactory;
    protected $table = 'ocupaciones';

    protected $fillable = [
        'cantPersonas',
        'idAmbiente'
    ];
    public $timestamps = false;
}
