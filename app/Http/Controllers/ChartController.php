<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function getCountriesData() {
        $ambientesPorPais = Ambiente::select('pais', DB::raw('count(*) as total'))->groupBy('pais')->get();
        return inertia('home', ['ambientesPorPais' => $ambientesPorPais]);
    }
}
