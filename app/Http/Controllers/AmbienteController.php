<?php

namespace App\Http\Controllers;

use App\Enums\AlturaEnum;
use App\Enums\CalidadEnum;
use App\Enums\DensidadEnum;
use App\Enums\TipoHabEnum;
use App\Models\Ambiente;
use App\Models\Local;
use App\Models\Ocupacion;
use App\Models\Ubicacion;
use App\Models\User;
use App\Models\Ventana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AmbienteController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombreAmbiente' => [
                'required',
                'string',
                'max:40',
                Rule::unique('ambientes', 'nombre')->where(function ($query) use ($request) {
                    return $query->where('idUsuario', $request['idUser']);
                })
            ],
            'alturaSelect' => ['required'],
            'densidadSelect' => ['required'],
            'tipoHabitacion' => ['required'],
            'calidadVentana' => ['required'],
            'longitud' => ['required'],
            'latitud' => ['required']
        ]);

        $ambiente = Ambiente::create([
            'nombre' => $validatedData['nombreAmbiente'],
            'idUsuario' => $request['idUser'],
        ]);
        $ambiente->save();
        $ambiente->ubicacion = Ubicacion::create([
            'altura' => AlturaEnum::from($validatedData['alturaSelect']),
            'latitud' => $validatedData['latitud'],
            'longitud' => $validatedData['longitud'],
            'densidad' => DensidadEnum::from($validatedData['densidadSelect']),
            'idAmbiente' => $ambiente->id,
        ]);
        $ambiente->ubicacion->save();
        $ambiente->local = Local::create([
            'tipoHabitacion' => TipoHabEnum::from($validatedData['tipoHabitacion']),
            'largo' => $request['largoAmbiente'],
            'ancho' => $request['anchoAmbiente'],
            'alto' => $request['altoAmbiente'],
            'idAmbiente' => $ambiente->id,
        ]);
        $ambiente->local->save();
        $ambiente->ventana = Ventana::create([
            'calidad' => CalidadEnum::from($validatedData['calidadVentana']),
            'largo' => $request['largoVentana'],
            'alto' => $request['altoVentana'],
            'corrediza' => true,
            'idAmbiente' => $ambiente->id,
        ]);
        $ambiente->ventana->save();
        $ambiente->ocupacion = Ocupacion::create([
            'cantPersonas' => 1,
            'idAmbiente' => $ambiente->id,
        ]);
        $ambiente->ocupacion->save();

        return response()->json(['message' => 'Ambiente creado correctamente', 'data' => $ambiente->with(['ubicacion', 'ventana', 'local', 'ocupacion'])->get()->where('id', $ambiente->id)], 200);
    }

    public function update(Request $request)
    {
        $ambiente = Ambiente::findOrFail($request['idAmbiente']);
        $validatedData = $request->validate([
            'nombreAmbiente' => [
                'required',
                'string',
                'max:40',
                Rule::unique('ambientes', 'nombre')
                    ->where(function ($query) use ($request) {
                        return $query->where('idUsuario', $request['idUser']);
                    })
                    ->ignore($ambiente->id) // Ignora el ambiente actual
            ],
            'alturaSelect' => ['required'],
            'densidadSelect' => ['required'],
            'tipoHabitacion' => ['required'],
            'calidadVentana' => ['required']
        ]);

        $ambiente->nombre = $validatedData['nombreAmbiente'];
        $ambiente->save();

        $ambiente->ubicacion->altura = AlturaEnum::from($validatedData['alturaSelect']);
        $ambiente->ubicacion->densidad = DensidadEnum::from($validatedData['densidadSelect']);
        $ambiente->ubicacion->latitud = $request['latitud'];
        $ambiente->ubicacion->longitud = $request['longitud'];
        $ambiente->ubicacion->save();

        $ambiente->local->largo = $request['largoAmbiente'];
        $ambiente->local->ancho = $request['anchoAmbiente'];
        $ambiente->local->alto = $request['altoAmbiente'];
        $ambiente->local->tipoHabitacion = TipoHabEnum::from($validatedData['tipoHabitacion']);
        $ambiente->local->save();

        $ambiente->ventana->largo = $request['largoVentana'];
        $ambiente->ventana->alto = $request['altoVentana'];
        $ambiente->ventana->calidad = CalidadEnum::from($validatedData['calidadVentana']);
        $ambiente->ventana->save();

        return response()->json(['message' => 'Ambiente creado correctamente', 'data' => $ambiente->with(['ubicacion', 'ventana', 'local', 'ocupacion'])->get()->where('id', $ambiente->id)], 200);
    }

    public function obtenerAmbientes($userId)
    {
        $user = User::findOrFail($userId);
        return response()->json(['message' => 'Ambientes obtenidos correctamente', 'data' => $user->ambiente()->with(['ubicacion', 'ventana', 'local', 'ocupacion'])->get()], 200);
    }
}
