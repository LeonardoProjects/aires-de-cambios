<?php

namespace App\Http\Functions;

use App\Enums\CalidadEnum;

class SecondaryFunctions
{
    public static function area(float $largo, float $ancho)
    {
        return $largo * $ancho;
    }

    public static function infiltracion_m3_dividido_hora_por_m2_abertura(CalidadEnum $calidadVentana, float $viento_Ch)
    {
        switch ($calidadVentana) {
            case CalidadEnum::Normal:
                return (3.18 * pow(M_E, (0.283 * $viento_Ch)));
            case CalidadEnum::Mejorada:
                return (1.15 * pow(M_E, (0.305 * $viento_Ch)));
            case CalidadEnum::Reforzada:
                return (0.46 * pow(M_E, (0.277 * $viento_Ch)));
            default:
                return 0;
        }
    }

    public static function infiltraciones_totales_de_abertura_m3_dividido_hora(float $infiltracionesPorMetroAbertura, float $areaVentana)
    {
        return $infiltracionesPorMetroAbertura * $areaVentana;
    }
}
