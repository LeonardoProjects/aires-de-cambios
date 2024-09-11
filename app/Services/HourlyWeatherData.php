<?php

namespace App\Services;

class HourlyWeatherData 
{
    private $hour;
    private $wind_kph;
    private $humidity;
    private $condition = [];

    public function __construct($hour, $wind_kph, $humidity, $condition) {
        $this->hour = $hour;
        $this->wind_kph = $wind_kph;
        $this->humidity = $humidity;
        $this->condition = [
            'text' => $condition['text'],
            'icon' => $condition['icon'],
            'code' => $condition['code']
        ];
    }

    public function getHour() {
        return $this->hour;
    }

    public function getWindKph() {
        return $this->wind_kph;
    }

    public function getHumidity() {
        return $this->humidity;
    }

    public function getCondition() {
        return $this->condition;
    }
}