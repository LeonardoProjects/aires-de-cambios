<?php

namespace App\Http\Controllers;

use App\Enums\AlturaEnum;
use App\Enums\CalidadEnum;
use App\Enums\DensidadEnum;
use App\Enums\MensajesEnum;
use App\Enums\TipoHabEnum;
use Illuminate\Http\Request;
use App\Http\Functions\Functions;

class ResultadoController extends Controller
{
    //Realizar el calculo utilizando todas las funciones
    //Devolver en un formato JSON para el front cm, temperatura 
    //Al final meter CSS al login y registro

    public function calcularResultado(float $latitud, float $longitud, DensidadEnum $densidad,
     AlturaEnum $altura, TipoHabEnum $tipoHabitacion, float $largoAmbiente, float $anchoAmbiente,
      float $altoAmbiente, int $cantPersonas, float $altoVentana, float $largoVentana, CalidadEnum $calidadVentana)
    {
        //Recibir los datos del request

        //Datos de ubicación
        //Obtener Latitud y longitud del mapa luego
        //latitud = -32.319339;
        //longitud = -58.076054;
        /* if ($request->densidad == "Frente al mar") {
            $densidad = DensidadEnum::FrenteAlMar;
        } elseif ($request->densidad == "El campo") {
            $densidad = DensidadEnum::ElCampo;
        } elseif ($request->densidad == "Un barrio poco denso") {
            $densidad = DensidadEnum::UnBarrioPocoDenso;
        } elseif ($request->densidad == "Un barrio muy denso") {
            $densidad = DensidadEnum::UnBarrioMuyDenso;
        } else {
            $densidad = DensidadEnum::ElCentroConEdificiosAltos;
        }

        if ($request->altura == "Planta baja") {
            $altura = AlturaEnum::PrimerOsegundoPiso;
        } elseif ($request->altura == "1° o 2° piso") {
            $altura = AlturaEnum::TercerAQuintoPiso;
        } elseif ($request->altura == "3° a 5° piso") {
            $altura = AlturaEnum::TercerAQuintoPiso;
        } else {
            $altura = AlturaEnum::SextoPisoOMas;
        }

        //Datos de Ambiente
        if ($request->tipoHabitacion == "Dormitorio") {
            $tipoHabitacion = TipoHabEnum::Dormitorio;
        } else {
            $tipoHabitacion = TipoHabEnum::EstarComedor;
        }
        $largoAmbiente = $request->input('largoAmbiente');
        $anchoAmbiente = $request->input('anchoAmbiente');
        $altoAmbiente = $request->input('altoAmbiente');

        //Datos de ocupación
        $cantPersonas = $request->input('cantPersonas');

        //Datos de Ventana
        $largoVentana = $request->input('largoVentana');
        $altoVentana = $request->input('anchoVentana');
        if ($request->calidadVentana == "Normal") {
            $calidadVentana = CalidadEnum::Normal;
        } elseif ($request->calidadVentana == "Mejorada") {
            $calidadVentana = CalidadEnum::Mejorada;
        } else {
            $calidadVentana = CalidadEnum::Reforzada;
        } */

        //dd($largoAmbiente, $anchoAmbiente, $altoAmbiente, $largoVentana, $altoVentana, $altura, $tipoHabitacion);
        //$areaAmbiente = Functions::area($largoAmbiente, $anchoAmbiente);
        $areaVentana = Functions::area($largoVentana, $altoVentana);
        $volumenAmbiente = Functions::volumLocal($largoAmbiente, $anchoAmbiente, $altoAmbiente);
        $volDispXPersona = Functions::volDispXPersona($volumenAmbiente, $cantPersonas);

        //Calculo de caudal de aire a renovar
        //Método AN

        $ventilacionXco2 = Functions::ventilacionXco2($volDispXPersona);
        $caudalAireARenovarXco2 = Functions::caudalXco2($ventilacionXco2, $cantPersonas);

        $ventilacionXh2o = Functions::ventilacionXco2($volDispXPersona);
        $caudalAireARenovarXh2o = Functions::caudalXh2o($ventilacionXh2o, $cantPersonas);

        //Método CTE
        $caudalMetodoCTE = Functions::caudalAireRenovar($tipoHabitacion, $cantPersonas);

        //Calculo de infiltraciones de aire

        $coeficienteDeRugosidad = Functions::obtenerCorreccionCh($altura, $densidad);
        //dd($coeficienteDeRugosidad);
        $viento = 40.6;
        $vientoCh = Functions::obtenerVientoCh($coeficienteDeRugosidad, $viento);
        $infiltracionesDeAire = Functions::infiltracionDeAire($calidadVentana, $vientoCh);
        $infiltracionesTotales = Functions::infiltracionesTotales($infiltracionesDeAire, $areaVentana);
        
        //Comparativo de caudal a renovar por diferentes métodos
        $caudalAireMaximo = Functions::comparativoCaudalDiffMetodos($caudalAireARenovarXco2, $caudalAireARenovarXh2o);
        $caudalAireARenovar = Functions::caudalAireCOMPinfiltraciones($caudalAireMaximo, $infiltracionesTotales);
        dd($caudalAireARenovar);
        
        //Calculo de ventilación unilateral
        if($caudalAireARenovar != -1){
            $AE = Functions::obtenerAE($caudalAireARenovar, $vientoCh);
            $centimetrosParaAbrirVentana = Functions::aperturaVentana($AE, $altoVentana);
            $alerta = Functions::determinarAlerta(18.4, $viento, true, false);
        }else{
            $centimetrosParaAbrirVentana = 0;
            $alerta = MensajesEnum::Nada;
        }

        //Mensaje final a comunicar
        dd($centimetrosParaAbrirVentana, $alerta);
    }

    function pruebita(){
        return self::calcularResultado(-32.319339, -58.076054, DensidadEnum::UnBarrioMuyDenso, AlturaEnum::PrimerOsegundoPiso, TipoHabEnum::Dormitorio, 6, 3, 3, 2, 1.5, 1.5, CalidadEnum::Mejorada);
    }
}
