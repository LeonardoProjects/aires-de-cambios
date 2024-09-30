<?php

namespace App\Http\Controllers;

use App\Http\Functions\Functions;
use App\Models\User;
use App\Services\ForecastWeatherData;
use Illuminate\Support\Facades\Http;

class ResultadosController extends Controller
{
    public function index($idAmbiente, $cantPersonas)
    {
        $forecastData = $this->getApiData();
        $objectAPI = $this->processApiData($forecastData);
        $ambiente = User::find(1)->ambiente()->where('id', $idAmbiente)->first();
        $firstDayData = [];
        $secondDayData = [];
        $resultData = [];
        foreach ($objectAPI->getFirstDateData() as $key => $hourData) {
            $resultado = Functions::calcularResultado($ambiente, $hourData, $cantPersonas);
            $firstDayData[$key] = [
                'hora' => sprintf('%02d:00', $key),
                'apertura' => $resultado['apertura'],
                'alerta' => $resultado['alerta'],
            ];
        };
        foreach ($objectAPI->getSecondDateData() as $key => $hourData) {
            $resultado = Functions::calcularResultado($ambiente, $hourData, $cantPersonas);
            $secondDayData[$key] = [
                'hora' => sprintf('%02d:00', $key),
                'apertura' => $resultado['apertura'],
                'alerta' => $resultado['alerta'],
            ];
        };
        $resultData = [
            'firstDayData' => $firstDayData,
            'secondDayData' => $secondDayData,
            'localTime' => $objectAPI->getLocalTime(),
        ];
        return response()->json($resultData);
    }

    /*
     * Obtiene los datos de la API, y los devuelve en formato Json
     */
    public function getApiData()
    {
        $response = Http::get('https://api.weatherapi.com/v1/forecast.json?key=8e0af554efe14fa99f7192419240509&q=-32.319339,-58.076054&days=2&lang=es');
        $forecastData = $response->json();
        return $forecastData;
    }

    /*
     * Procesa los datos de la API, y los devuelve como objeto ForecastWeatberData
     */
    public function processApiData($forecastData) {
        $objectAPI = new ForecastWeatherData($forecastData['location']['localtime'],$forecastData['forecast']['forecastday'][0]['hour'],$forecastData['forecast']['forecastday'][1]['hour'] );
        return $objectAPI;
    }
}
