<?php

namespace App\Http\Functions;

use App\Enums\AlturaEnum;
use App\Enums\CalidadEnum;
use App\Enums\DensidadEnum;
use App\Enums\MensajesEnum;
use App\Enums\TipoHabEnum;

class Functions
{
    //Variables estáticas que contienen los caudales mínimos para cada tipo de habitación
    private static $caudal_minimo_x_persona_DORMITORIO = 5.0;
    private static $caudal_minimo_x_persona_ESTARoCOMEDOR = 3.0;

    //Se genera una matriz con los Enums de Altura y densidad, para que se guarde el valor asociado a la combinación de ambos
    private static array $matrizCh = [
        AlturaEnum::PlantaBaja->value => [
            DensidadEnum::FrenteAlMar->value => 1.05,
            DensidadEnum::ElCampo->value => 0.70,
            DensidadEnum::UnBarrioPocoDenso->value => 0.50,
            DensidadEnum::UnBarrioMuyDenso->value => 0.20,
            DensidadEnum::ElCentroConEdificiosAltos->value => 0.00,
        ],
        AlturaEnum::PrimerOsegundoPiso->value => [
            DensidadEnum::FrenteAlMar->value => 1.15,
            DensidadEnum::ElCampo->value => 0.85,
            DensidadEnum::UnBarrioPocoDenso->value => 0.65,
            DensidadEnum::UnBarrioMuyDenso->value => 0.40,
            DensidadEnum::ElCentroConEdificiosAltos->value => 0.20,
        ],
        AlturaEnum::TercerAQuintoPiso->value => [
            DensidadEnum::FrenteAlMar->value => 1.25,
            DensidadEnum::ElCampo->value => 1.00,
            DensidadEnum::UnBarrioPocoDenso->value => 0.80,
            DensidadEnum::UnBarrioMuyDenso->value => 0.60,
            DensidadEnum::ElCentroConEdificiosAltos->value => 0.40,
        ],
        AlturaEnum::SextoPisoOMas->value => [
            DensidadEnum::FrenteAlMar->value => 1.25,
            DensidadEnum::ElCampo->value => 1.15,
            DensidadEnum::UnBarrioPocoDenso->value => 0.95,
            DensidadEnum::UnBarrioMuyDenso->value => 0.81,
            DensidadEnum::ElCentroConEdificiosAltos->value => 0.60,
        ]
    ];


    /*  Para el area local se envía los datos como están descritos en la Función. 
        Para el área de ventana reemplazar el $ancho por $alto.
        Ambos resultados en m2
    */
    public static function area(float $largo, float $ancho)
    {
        return round($largo * $ancho, 2);
    }

    //Función para obtener la corrección del coeficiente de habitabilidad según la altura y densidad del barrio, devuelve -1 en caso de no encontrar alguno de los enums en la matriz
    public static function obtenerCorreccionCh(AlturaEnum $altura, DensidadEnum $densidad)
    {
        if (isset(self::$matrizCh[$altura->value][$densidad->value])) {
            return self::$matrizCh[$altura->value][$densidad->value];
        } else {
            return -1;
        }
    }

    //Se calcula el viento ch multiplicando el indice de rugosidad (CorrecionCh) por el viento en m/s, siendo este último obtenido por la API. El resultado es en m/s.
    public static function obtenerVientoCh(float $correccionCh, float $viento)
    {
        return round($correccionCh * $viento, 2);
    }

    //El calculo AE se refiere al cálculo del área de entrada. El resultado es en m2.
    public static function obtenerAE(float $caudalARenovar, float $vientoCh)
    {
        return round($caudalARenovar / ($vientoCh * 0.025), 2);
    }

    //Función para calcular el volumen local, en base a todas las dimensiones del ambiente. El resultado es en m3
    public static function volumLocal(float $largo, float $ancho, float $alto)
    {
        return round($largo * $ancho * $alto, 2);
    }

    //Función para calcular el volumen disponible por persona, en base al volumen local. El resultado es en m3/persona.
    public static function volDispXPersona(float $volumen_local, int $cantPersonas)
    {
        return round($volumen_local / $cantPersonas, 2);
    }

    /* Función para calcular el caudal de aire a renovar por persona mediante el método CTE. 
    El resultado es en m3/h. */
    public static function caudalAireRenovar(TipoHabEnum $tipoHabitacion, int $cantPersonas)
    {
        $caudal_minimo = 0.0;
        switch ($tipoHabitacion) {
            case TipoHabEnum::Dormitorio:
                round($caudal_minimo = self::$caudal_minimo_x_persona_DORMITORIO * $cantPersonas, 2);
                break;
            case TipoHabEnum::EstarComedor:
                round($caudal_minimo = self::$caudal_minimo_x_persona_ESTARoCOMEDOR * $cantPersonas, 2);
                break;
        }
        return round(($caudal_minimo / 1000) * 3600, 2);
    }

    //Función para calcular la infiltraciones de aire (m3/h) * m2 de abertura
    public static function infiltracionDeAire(CalidadEnum $calidadVentana, float $viento_Ch)
    {
        switch ($calidadVentana) {
            case CalidadEnum::Normal:
                return round((3.18 * pow(M_E, (0.283 * $viento_Ch))), 1);
            case CalidadEnum::Mejorada:
                return round((1.15 * pow(M_E, (0.305 * $viento_Ch))), 1);
            case CalidadEnum::Reforzada:
                return round((0.46 * pow(M_E, (0.277 * $viento_Ch))), 1);
        }
    }

    //Funcion para calcular la infiltraciones totales por abertura (m3/h)
    public static function infiltracionesTotales(float $infiltracionesDeAire, float $areaVentana)
    {
        return round($infiltracionesDeAire * $areaVentana, 1);
    }

    //Función para calcular la apertura de la ventana en centímentros
    public static function aperturaVentana(float $valorAE, float $alturaVentana)
    {
        return round((($valorAE / $alturaVentana) * 100), 0);
    }
//-1 no tiene alerta mas adelante se podria pasar el modelo de la API 
    public static function determinarMensajes(float $temperatura, float $velocidadViento, bool $llueve)
    {
        $resumenMensajes = [];

    if ($temperatura > 18) {
        $resumenMensajes[] = MensajesEnum::Agradable;
    }
    if ($temperatura < 10) {
        $resumenMensajes[] = MensajesEnum::Frio;
    }
    if ($velocidadViento > 40) {
        $resumenMensajes[] = MensajesEnum::VientosFuertes;
    }
    if ($llueve) {
        $resumenMensajes[] = MensajesEnum::Tormenta;
    }

    if (empty($resumenMensajes)) {
        $resumenMensajes[] = -1;
    }

    return $resumenMensajes;
    }
}
