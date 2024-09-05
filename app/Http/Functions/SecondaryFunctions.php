<?php

namespace App\Http\Functions;

use App\Enums\TipoHabEnum;

class SecondaryFunctions
{
    public static $caudal_minimo_x_persona_DORMITORIO = 5.0;

    public static $caudal_minimo_x_persona_ESTARoCOMEDOR = 3.0;

    public static function area(float $largo, float $ancho)
    {
        return $largo * $ancho;
    }

    public static function volumen_local(float $largo, float $ancho, float $alto)
    {
        return $largo * $ancho * $alto;
    }

    public static function volumen_disponible_x_persona(float $volumen_local, int $cantPersonas)
    {
        return $volumen_local / $cantPersonas;
    }

    public static function caudal_aire_renovar_CTE(TipoHabEnum $tipoHabitacion, int $cantPersonas)
    {
        $caudal_minimo = 0.0;
        switch ($tipoHabitacion) {
            case TipoHabEnum::Dormitorio:
                $caudal_minimo = self::$caudal_minimo_x_persona_DORMITORIO * $cantPersonas;
                break;
            case TipoHabEnum::EstarComedor:
                $caudal_minimo = self::$caudal_minimo_x_persona_ESTARoCOMEDOR * $cantPersonas;
                break;
        }
        return ($caudal_minimo / 1000) * 3600;
    }
}
