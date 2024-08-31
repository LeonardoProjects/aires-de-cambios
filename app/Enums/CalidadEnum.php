<?php

namespace App\Enums;

use App\Enums\BaseEnum;

enum CalidadEnum: string {

    use BaseEnum;

    case Normal = 'Normal';
    case Mejorada = 'Mejorada';
    case Reforzada = 'Reforzada';
}