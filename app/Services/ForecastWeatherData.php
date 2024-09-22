<?php

namespace App\Services;

class ForecastWeatherData
{
    private $localtime;
    private $firstDate = [];
    private $secondDate = [];

    public function __construct($localtime, $firstDate, $secondDate) {
        $this->localtime = $localtime;
        $this->firstDate = $this->generateDateData($firstDate);
        $this->secondDate = $this->generateDateData($secondDate);
    }

    private function generateDateData($DatehourlyData)
    {
        $hourlyCollection = [];
        foreach ($DatehourlyData as $index => $data) {
            $hourlyCollection[] = new HourlyWeatherData(
                $index,
                $data['temp_c'],
                $data['wind_kph'],
                $data['humidity'],
                $data['condition']
            );
        }
        return $hourlyCollection;
    }

    public function getLocaltime()
    {
        return $this->localtime;
    }

    public function getFirstDateData()
    {
        return $this->firstDate;
    }

    public function getSecondDateData()
    {
        return $this->secondDate;
    }

    
}
