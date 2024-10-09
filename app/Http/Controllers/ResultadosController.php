<?php

namespace App\Http\Controllers;

use App\Enums\AlturaEnum;
use App\Enums\CalidadEnum;
use App\Enums\DensidadEnum;
use App\Enums\TipoHabEnum;
use App\Http\Functions\Functions;
use App\Models\Ambiente;
use App\Models\Local;
use App\Models\Ocupacion;
use App\Models\Ubicacion;
use App\Models\User;
use App\Models\Ventana;
use App\Services\ForecastWeatherData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ResultadosController extends Controller
{
    public $timezone = null;
    public function index(Request $request)
    {
        $cantPersonas = $request['cantPersonas'];
        $idAmbiente = $request['idAmbiente'];
        if ($idAmbiente == -2) {
            $ambiente = new Ambiente([
                'nombre' => $request['ambiente']['nombreAmbiente'],
                'idUsuario' => null
            ]);

            $ambiente->ubicacion = new Ubicacion([
                'altura' => AlturaEnum::from($request['ambiente']['alturaSelect']),
                'latitud' => $request['ambiente']['latitud'],
                'longitud' => $request['ambiente']['longitud'],
                'densidad' => DensidadEnum::from($request['ambiente']['densidadSelect']),
                'idAmbiente' => null,
            ]);

            $ambiente->local = new Local([
                'tipoHabitacion' => TipoHabEnum::from($request['ambiente']['tipoHabitacion']),
                'largo' => $request['ambiente']['largoAmbiente'],
                'ancho' => $request['ambiente']['anchoAmbiente'],
                'alto' => $request['ambiente']['altoAmbiente'],
                'idAmbiente' => null,
            ]);

            $ambiente->ventana = new Ventana([
                'calidad' => CalidadEnum::from($request['ambiente']['calidadVentana']),
                'largo' => $request['ambiente']['largoVentana'],
                'alto' => $request['ambiente']['altoVentana'],
                'corrediza' => true,
                'idAmbiente' => null,
            ]);

            $ambiente->ocupacion = new Ocupacion([
                'cantPersonas' => $cantPersonas,
                'idAmbiente' => null,
            ]);
        } else {
            $ambiente = User::find(1)->ambiente()->where('id', $idAmbiente)->first();
        }
        $ubi = $ambiente->ubicacion;
        $forecastData = $this->getApiData($ubi->latitud, $ubi->longitud);
        $objectAPI = $this->processApiData($forecastData);
        $firstDayData = [];
        $secondDayData = [];
        $resultData = [];
        foreach ($objectAPI->getFirstDateData() as $key => $hourData) {
            $resultado = Functions::calcularResultado($ambiente, $hourData, $cantPersonas);
            $firstDayData[$key] = [
                'hora' => sprintf('%02d:00', $key),
                'apertura' => $resultado['apertura'],
                'alertas' => $resultado['alertas'],
            ];
        };
        foreach ($objectAPI->getSecondDateData() as $key => $hourData) {
            $resultado = Functions::calcularResultado($ambiente, $hourData, $cantPersonas);
            $secondDayData[$key] = [
                'hora' => sprintf('%02d:00', $key),
                'apertura' => $resultado['apertura'],
                'alertas' => $resultado['alertas'],
            ];
        };
        $resultData = [
            'firstDayData' => $firstDayData,
            'secondDayData' => $secondDayData,
            'localTime' => $objectAPI->getLocalTime(),
            'timezone' => $this->timezone
        ];
        return response()->json($resultData);
    }

    /*
     * Obtiene los datos de la API, y los devuelve en formato Json
     */
    public function getApiData($latitud, $longitud)
    {
        $latParsed = (string)$latitud;
        $longParsed = (string)$longitud;
        $response = Http::get("https://api.weatherapi.com/v1/forecast.json?key=8e0af554efe14fa99f7192419240509&q={$latParsed},{$longParsed}&days=2&lang=es");
        $forecastData = $response->json();
        return $forecastData;
    }

    /*
     * Procesa los datos de la API, y los devuelve como objeto ForecastWeatberData
     */
    public function processApiData($forecastData)
    {
        $this->timezone = $forecastData['location']['tz_id'];
        $objectAPI = new ForecastWeatherData($forecastData['location']['localtime'], $forecastData['forecast']['forecastday'][0]['hour'], $forecastData['forecast']['forecastday'][1]['hour']);
        return $objectAPI;
    }
}
