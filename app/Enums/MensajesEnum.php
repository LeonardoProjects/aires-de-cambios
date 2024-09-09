<?php

namespace App\Enums;

use App\Enums\BaseEnum;

enum MensajesEnum: string {

    use BaseEnum;

    case Agradable = 'Aprovechar temperaturas agradables (mayor a 18° C)';
    case Frio = 'Precaución por bajas temperaturas (menor a 10° C)';
    case VientosFuertes = 'Precaución por vientos fuertes (mayores a 40 km/h)';
    case Lluvia = 'Precaución por lluvias';
    case Tormenta = 'Precaución por tormentas fuertes';
    case Nada = 'Las infiltraciones cubren la ventilación necesaria';
}