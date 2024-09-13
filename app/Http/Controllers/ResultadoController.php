<?php

namespace App\Http\Controllers;

use App\Http\Functions\Functions;
use App\Models\Ambiente;
use App\Services\HourlyWeatherData;

class ResultadoController extends Controller
{
    public function calcularResultado(Ambiente $ambiente, HourlyWeatherData $datosAPI)
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
        $areaVentana = Functions::area($largoVentana, $altoVentana);
        $volumenAmbiente = Functions::volumLocal($largoAmbiente, $anchoAmbiente, $altoAmbiente);
        $volDispXPersona = Functions::volDispXPersona($volumenAmbiente, $cantPersonas);

        //Calculo de caudal de aire a renovar
        //Método AN

        $ventilacionXco2 = Functions::ventilacionXco2($volDispXPersona);
        $caudalAireARenovarXco2 = Functions::caudalXco2($ventilacionXco2, $cantPersonas);

        $ventilacionXh2o = Functions::ventilacionXh2o($volDispXPersona);
        $caudalAireARenovarXh2o = Functions::caudalXh2o($ventilacionXh2o, $cantPersonas);

        //Método CTE
        $caudalMetodoCTE = Functions::caudalAireRenovar($tipoHabitacion, $cantPersonas);
        //Calculo de infiltraciones de aire

        $coeficienteDeRugosidad = Functions::obtenerCorreccionCh($altura, $densidad);
        $vientoCh = Functions::obtenerVientoCh($coeficienteDeRugosidad, $vientoMXs);
        $infiltracionesDeAire = Functions::infiltracionDeAire($calidadVentana, $vientoCh);
        $infiltracionesTotales = Functions::infiltracionesTotales($infiltracionesDeAire, $areaVentana);
        //Comparativo de caudal a renovar por diferentes métodos
        $caudalAireMaximo = Functions::comparativoCaudalDiffMetodos($caudalMetodoCTE, $caudalAireARenovarXh2o);
        $caudalAireARenovar = Functions::caudalAireCOMPinfiltraciones($caudalAireMaximo, $infiltracionesTotales);

        //Calculo de ventilación unilateral
        if ($caudalAireARenovar != -1) {
            $AE = Functions::obtenerAE($caudalAireARenovar, $vientoCh);
            $centimetrosParaAbrirVentana = Functions::aperturaVentana($AE, $altoVentana);
        }

        $alerta = Functions::determinarAlerta($temperatura, $vientoKXh, $lluvia, $tormenta);
        return [
            'apertura' => $centimetrosParaAbrirVentana,
            'alerta' => $alerta
        ];
    }
}
