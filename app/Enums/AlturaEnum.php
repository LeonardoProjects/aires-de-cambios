<?php

namespace App\Enums;

use App\Enums\BaseEnum;

enum AlturaEnum: string {

    use BaseEnum;

    case PlantaBaja = 'Planta baja';
    case PrimerOsegundoPiso = '1° o 2° piso';
    case TercerAQuintoPiso = '3° a 5° piso';
    case SextoPisoOMas = '6° piso o más';
}