<?php

namespace App\Http\Controllers;

use App\Models\survey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'util' => 'required|in:true,false',
            'recomendar' => 'required|in:true,false',
            'puntuacion' => 'required|integer|min:1|max:5',
        ]);

        $validatedData['util'] = $validatedData['util'] === 'true';
        $validatedData['recomendar'] = $validatedData['recomendar'] === 'true';

        survey::create([
            'user_id' => $request['idUser'],
            'util' => filter_var($validatedData['util'], FILTER_VALIDATE_BOOLEAN),
            'recomendar' => filter_var($validatedData['recomendar'], FILTER_VALIDATE_BOOLEAN),
            'puntuacion' => $validatedData['puntuacion'],
        ]);
        
        $user = Auth::user();
        $user->survey_completed = true;
        $user->save();

        return redirect()->back()->with('success', 'Gracias por completar la encuesta!');
    }

    public function checkSurveyStatus(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::find($validatedData['user_id']);

        return response()->json([
            'survey_completed' => $user->survey_completed
        ]);
    }
}
