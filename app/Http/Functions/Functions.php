<?php

namespace App\Http\Functions;

use App\Enums\AlturaEnum;
use App\Enums\CalidadEnum;
use App\Enums\DensidadEnum;
use App\Enums\MensajesEnum;
use App\Enums\TipoHabEnum;
use App\Models\Ambiente;
use App\Services\HourlyWeatherData;

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

    //Valores utilizados para calculo de caudal de aire a renovar. Por método AN
    private static $m3_p = [
        2.2,
        3,
        6,
        9,
        9.4,
        12
    ];
    private static $h2o = [
        64,
        52,
        29,
        19,
        null,
        13
    ];
    private static $co2 = [
        39.5,
        33,
        16,
        7,
        6,
        null
    ];

    /*  Para el area local se envía los datos como están descritos en la función. 
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
    //$correccionCh se obtiene de la función: obtenerCorreccionCh()
    public static function obtenerVientoCh(float $correccionCh, float $viento)
    {
        return round($correccionCh * $viento, 2);
    }

    //El calculo AE se refiere al cálculo del área de entrada. El resultado es en m2.
    /* 
        $caudalARenovar se obtiene en la función: caudalAireCOMPinfiltraciones()
        $vientoCh se obtiene en la función: obtenerVientoCh()
    */
    public static function obtenerAE(float $caudalARenovar, float $vientoCh)

    {
        return round(round($caudalARenovar / 3600, 3) / ($vientoCh * 0.025), 3);
    }

    //Función para calcular el volumen local, en base a todas las dimensiones del ambiente. El resultado es en m3
    public static function volumLocal(float $largo, float $ancho, float $alto)
    {
        return round($largo * $ancho * $alto, 2);
    }

    //Función para calcular el volumen disponible por persona, en base al volumen local. El resultado es en m3/persona.
    //Depende de función: volumLocal()
    public static function volDispXPersona(float $volumen_local, int $cantPersonas)
    {
        return round($volumen_local / $cantPersonas, 2);
    }

    /* 
        Función para calcular el caudal de aire a renovar por persona mediante el método CTE. 
        El resultado es en m3/h. 
    */
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
    //$viento_Ch se obtiene en la función: obtenerVientoCh()
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
    //Depende de la función: infiltracionDeAire()
    public static function infiltracionesTotales(float $infiltracionesDeAire, float $areaVentana)
    {
        return round($infiltracionesDeAire * $areaVentana, 1);
    }

    //Función para calcular la apertura de la ventana en centímentros
    public static function aperturaVentana(float $valorAE, float $alturaVentana)
    {
        return round((($valorAE / $alturaVentana) * 100), 0);
    }
    //Esta funcion depende de los datos de la API
    public static function determinarAlerta(float $temperatura, float $velocidadViento, bool $llueve, bool $tormenta)
    {
        //Se manejará una alerta y la prioridad será tomada primero el viento, segundo la tormenta,
        // tercero si hay lluvia cuarto va el frio y por ultimo la temperatura agradable.
        if ($velocidadViento > 40) {
            return MensajesEnum::VientosFuertes;
        }
        if ($tormenta) {
            return MensajesEnum::Tormenta;
        }
        if ($llueve) {
            return MensajesEnum::Lluvia;
        }
        if ($temperatura < 10) {
            return MensajesEnum::Frio;
        }
        if ($temperatura > 18) {
            return MensajesEnum::Agradable;
        }
        return MensajesEnum::Nada;
    }

    //Inicio método AN
    /* 
        Funcion para retornar ventilacion por CO2, en base a los valores estáticos para comparar.
        El resultado es en: m3/h-p
        $volDispXPersona se obtiene de la función: volDispXPersona() 
    */
    public static function ventilacionXco2(float $volDispXPersona)
    {
        if ($volDispXPersona > self::$m3_p[4]) {
            return self::$co2[4];
        } else if ($volDispXPersona < self::$m3_p[0]) {
            return self::$co2[0];
        } else {
            return round(62 - 11.7 * $volDispXPersona + 0.659 * pow($volDispXPersona, 2) +
                0.017 * pow($volDispXPersona, 3) - 0.00232 * pow($volDispXPersona, 4), 1);
        }
    }
    /* 
    Esta función depende de la función ventilacionXco2()
    El resultado es en: m3/h
    */
    public static function caudalXco2(float $ventilacionXco2, int $cantPersonas)
    {
        return round($ventilacionXco2 * $cantPersonas, 1);
    }

    /* 
        Función para retornar ventilación para H2O, en base a los valores estáticos para comparar.
        El resultado es en: m3/h-p
        $volDispXPersona se obtiene de la función: volDispXPersona()
    */
    public static function ventilacionXh2o(float $volDispXPersona)
    {
        if ($volDispXPersona > self::$m3_p[5]) {
            return self::$h2o[5];
        } else if ($volDispXPersona < self::$m3_p[0]) {
            return self::$h2o[0];
        } else {
            return round(121 - 36.5 * $volDispXPersona + 5.64 * pow($volDispXPersona, 2) -
                0.429 * pow($volDispXPersona, 3) + 0.0125 * pow($volDispXPersona, 4), 2);
        }
    }
    /* 
        Esta función depende de la función ventilacionXh2o()
        El resultado es en: m3/h
    */
    public static function caudalXh2o(float $ventilacionXh2o, int $cantPersonas)
    {
        return round($ventilacionXh2o * $cantPersonas, 1);
    }
    //Fin método AN

    /* 
        Funcion que toma el caudal de aire a renovar de ambos métodos (CTE y AN). 
        Y devuelve máximo de ambos, osea, el caudal de aire a renovar.
        El resultado es en: m3/h 

        q = caudal de aire a renovar
        $qCO2 se calcula en la función: caudalAireRenovar()
        $qH2O se calcula en la función: caudalXh2o()
    */
    public static function comparativoCaudalDiffMetodos(float $qCO2, float $qH2O)
    {
        return max($qCO2, $qH2O);
    }

    /* 
        Funcion que calcula si las infiltraciones cubren lo necesario para el caudal de aire a renovar.
        El resultado es en: m3/h o -1 si las infiltraciones cubren lo necesario.
        $caudalAireMAX se calcula en la función: comparativoCaudalDiffMetodos()
        $infiltracionesTotales se calcula en la función: infiltracionesTotales()
    */
    public static function caudalAireCOMPinfiltraciones(float $caudalAireMAX, float $infiltracionesTotales)
    {
        if ($infiltracionesTotales > $caudalAireMAX) {
            return -1;
            //Que retorne -1 significa que las infiltraciones cubren la ventilación
        } else {
            return $caudalAireMAX;
            //Si las infiltraciones no cubren el caudal de aire, retorna el caudal de aire
        }
    }

    /* Funcion final para realizar el calculo de apertura de ventana y obtener alertas*/
    public static function calcularResultado(Ambiente $ambiente, HourlyWeatherData $datosAPI)
    {
        $lluvia = false;
        $tormenta = false;
        $centimetrosParaAbrirVentana = 0;

        //Datos de ubicación
        $densidad = $ambiente->ubicacion->densidad;
        $altura = $ambiente->ubicacion->altura;

        //Datos de Ambiente
        $tipoHabitacion = $ambiente->local->tipoHabitacion;
        $largoAmbiente = $ambiente->local->largo;
        $anchoAmbiente = $ambiente->local->ancho;
        $altoAmbiente = $ambiente->local->alto;

        //Datos de ocupación
        $cantPersonas = $ambiente->ocupacion->cantPersonas;

        //Datos de Ventana
        $largoVentana = $ambiente->ventana->largo;
        $altoVentana = $ambiente->ventana->alto;
        $calidadVentana = $ambiente->ventana->calidad;

        //Datos climáticos y manejo de alertas API
        $vientoKXh = $datosAPI->getWindKph();
        $vientoMXs = ($datosAPI->getWindKph() / 3.6);
        $condicionClimatica = $datosAPI->getCondition();

        $codigosDeLluvia = [
            //Comentar significado code
            1150,
            1153,
            1180,
            1183,
            1186,
            1189,
            1192,
            1195,
            1198,
            1201,
            1240,
            1243,
            1246
        ];

        $codigosDeTormeta = [
            //Comentar significado code
            1207,
            1273,
            1252,
            1264,
            1276
        ];

        if (in_array($condicionClimatica['code'], $codigosDeLluvia)) {
            $lluvia = true;
        }

        if (in_array($condicionClimatica['code'], $codigosDeTormeta)) {
            $tormenta = true;
        }
        $temperatura = $datosAPI->getTemperatureC();

        //$areaAmbiente = Functions::area($largoAmbiente, $anchoAmbiente);
        $areaVentana = self::area($largoVentana, $altoVentana);
        $volumenAmbiente = self::volumLocal($largoAmbiente, $anchoAmbiente, $altoAmbiente);
        $volDispXPersona = self::volDispXPersona($volumenAmbiente, $cantPersonas);

        //Calculo de caudal de aire a renovar
        //Método AN

        $ventilacionXco2 = self::ventilacionXco2($volDispXPersona);
        $caudalAireARenovarXco2 = self::caudalXco2($ventilacionXco2, $cantPersonas);

        $ventilacionXh2o = self::ventilacionXh2o($volDispXPersona);
        $caudalAireARenovarXh2o = self::caudalXh2o($ventilacionXh2o, $cantPersonas);

        //Método CTE
        $caudalMetodoCTE = self::caudalAireRenovar($tipoHabitacion, $cantPersonas);
        //Calculo de infiltraciones de aire

        $coeficienteDeRugosidad = self::obtenerCorreccionCh($altura, $densidad);
        $vientoCh = self::obtenerVientoCh($coeficienteDeRugosidad, $vientoMXs);
        $infiltracionesDeAire = self::infiltracionDeAire($calidadVentana, $vientoCh);
        $infiltracionesTotales = self::infiltracionesTotales($infiltracionesDeAire, $areaVentana);
        //Comparativo de caudal a renovar por diferentes métodos
        $caudalAireMaximo = self::comparativoCaudalDiffMetodos($caudalMetodoCTE, $caudalAireARenovarXh2o);
        $caudalAireARenovar = self::caudalAireCOMPinfiltraciones($caudalAireMaximo, $infiltracionesTotales);

        //Calculo de ventilación unilateral
        if ($caudalAireARenovar != -1) {
            $AE = self::obtenerAE($caudalAireARenovar, $vientoCh);
            $centimetrosParaAbrirVentana = self::aperturaVentana($AE, $altoVentana);
        }

        $alerta = self::determinarAlerta($temperatura, $vientoKXh, $lluvia, $tormenta);
        return [
            'apertura' => $centimetrosParaAbrirVentana,
            'alerta' => $alerta
        ];
    }
}
