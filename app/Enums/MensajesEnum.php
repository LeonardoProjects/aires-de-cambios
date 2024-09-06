<?php

namespace App\Enums;

use App\Enums\BaseEnum;

enum MensajesEnum: string {

    use BaseEnum;

    case Agradable = 'Aprovechar temperaturas agradables';
    case Frio = 'Prevenir bajas temperaturas';
    case VientosFuertes = 'Precaución vientos fuertes';
    case Tormenta = 'Precaución lluvias y/o tormentas';
    case Nada = 'Nada';
}