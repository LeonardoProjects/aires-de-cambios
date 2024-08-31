<?php

namespace App\Enums;

use App\Enums\BaseEnum;

//TIPOHAB refiere al tipo de habitación seleccionada por el usuario
enum TipoHabEnum: string {

    use BaseEnum;

    case Dormitorio = 'Dormitorio';
    case EstarComedor = 'Estar o comedor';
}