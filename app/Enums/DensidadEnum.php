<?php

namespace App\Enums;

use App\Enums\BaseEnum;

enum DensidadEnum: string {

    use BaseEnum;

    case FrenteAlMar = 'Frente al mar';
    case ElCampo = 'El campo';
    case UnBarrioPocoDenso = 'Un barrio poco denso';
    case UnBarrioMuyDenso = 'Un barrio muy denso';
    case ElCentroConEdificiosAltos = 'El centro con edificios altos';
}