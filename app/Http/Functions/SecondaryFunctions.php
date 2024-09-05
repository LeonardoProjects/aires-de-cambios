<?php

namespace App\Http\Functions;

use App\Enums\AlturaEnum;
use App\Enums\DensidadEnum;

class SecondaryFunctions
{
    public static function area(float $largo, float $ancho) {
        return $largo * $ancho;
    }

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

    //Función para obtener la corrección del coeficiente de habitabilidad según la altura y densidad del barrio, devuelve -1 en caso de no encontrar alguno de los enums en la matriz
    public static function obtenerCorreccionCh (AlturaEnum $altura, DensidadEnum $densidad) {
        if (isset(self::$matrizCh[$altura->value][$densidad->value])){
            return self::$matrizCh[$altura->value][$densidad->value];
        }
        else {
            return -1;
        }
    }

    //Se calcula el viento ch multiplicando el indice de rugosidad (CorrecionCh) por el viento en m/s, siendo este último obtenido por la API. El resultado es en m/s.
    public static function obtenerVientoCh(float $correccionCh, float $viento){
        return $correccionCh * $viento;
    }

    //El calculo AE se refiere al cálculo del área de entrada. El resultado es en m2.
    public static function obtenerAE(float $caudalARenovar, float $vientoCh){
        return $caudalARenovar / ($vientoCh*0.025);
    }
}