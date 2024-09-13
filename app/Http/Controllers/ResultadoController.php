<?php

namespace App\Http\Controllers;

use App\Enums\MensajesEnum;
use App\Http\Functions\Functions;
use App\Models\Ambiente;
use App\Services\HourlyWeatherData;

class ResultadoController extends Controller
{
    //Realizar el calculo utilizando todas las funciones
    //Devolver en un formato JSON para el front cm, temperatura 
    //Al final meter CSS al login y registro

    public function calcularResultado(Ambiente $ambiente, HourlyWeatherData $datosAPI)
    {
        // Se obtienen las relaciones del modelo ambiente
        $ubicacionAmbiente = $ambiente->ubicacion;
        $localAmbiente = $ambiente->local;
        $ocupacionAmbiente = $ambiente->ocupacion;
        $ventanaAmbiente = $ambiente->ventana;

        //Datos de ubicación
        $densidad = $ubicacionAmbiente->densidad;
        $altura = $ubicacionAmbiente->altura;
        //Datos de Ambiente
        $tipoHabitacion = $localAmbiente->tipoHabitacion;
        $largoAmbiente = $localAmbiente->largo;
        $anchoAmbiente = $localAmbiente->ancho;
        $altoAmbiente = $localAmbiente->alto;

        //Datos de ocupación
        $cantPersonas = $ocupacionAmbiente->cantPersonas;

        //Datos de Ventana
        $largoVentana = $ventanaAmbiente->largo;
        $altoVentana = $ventanaAmbiente->alto;
        $calidadVentana = $ventanaAmbiente->calidad;

        //Datos climáticos y manejo de alertas API
        $vientoKXh = $datosAPI->getWindKph();
        $vientoMXs = ($datosAPI->getWindKph() / 3.6);
        $condicionClimatica = $datosAPI->getCondition();
        $codigosDeLluvia = [
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
        $codigosDeTormeta = [1207, 1273, 1252, 1264, 1276];
        if (in_array($condicionClimatica['code'], $codigosDeLluvia)) {
            $lluvia = true;
        } else {
            $lluvia = false;
        }
        if (in_array($condicionClimatica['code'], $codigosDeTormeta)) {
            $tormenta = true;
        } else {
            $tormenta = false;
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
            $alerta = Functions::determinarAlerta($temperatura, $vientoKXh, $lluvia, $tormenta);
        } else {
            $centimetrosParaAbrirVentana = 0;
            $alerta = MensajesEnum::Nada;
        }

        //Mensaje final a comunicar
        dd($centimetrosParaAbrirVentana, $alerta);
    }
}
