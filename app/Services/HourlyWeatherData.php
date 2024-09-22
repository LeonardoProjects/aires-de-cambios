<?php

namespace App\Services;

class HourlyWeatherData
{
    private $hour;
    private $temp_c;
    private $wind_kph;
    private $humidity;
    private $condition = [];

    public function __construct($hour, $temp_c, $wind_kph, $humidity, $condition)
    {
        $this->hour = $hour;
        $this->temp_c = $temp_c;
        $this->wind_kph = $wind_kph;
        $this->humidity = $humidity;
        $this->condition = [
            'text' => $condition['text'],
            'icon' => $condition['icon'],
            'code' => $condition['code']
        ];
    }

    public function getHour()
    {
        return $this->hour;
    }

    public function getTemperatureC()
    {
        return $this->temp_c;
    }

    public function getWindKph()
    {
        return $this->wind_kph;
    }

    public function getHumidity()
    {
        return $this->humidity;
    }

    public function getCondition()
    {
        return $this->condition;
    }
}
