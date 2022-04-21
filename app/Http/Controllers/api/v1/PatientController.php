<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    //
    public function index(Request $request) {

        $patients = Patient::search($request->buscar)
            ->take(10)->get();

        foreach ($patients as $patient) {
            $patient->full_name = Str::upper($patient->nombres . " " . $patient->apellido_paterno . " " . $patient->apellido_materno);
        }

        return response($patients);
    }
    
    public function login(Request $request) {

        $validated = $request->validate([
            'numero_documento' => 'required',
            'fecha_nacimiento' => 'required'
        ]);

        $paciente = User::where('usuario', $validated['numero_documento'])
            ->where('password', $validated['fecha_nacimiento'])
            ->first();

        if (!$paciente) {
            return response([
                'message' => 'Usuario y/o Password incorrectos'
            ], 401);
        }


        return response([
            'message' => 'Usuario logueado correctamente',
            'paciente' => $paciente
        ]);
    }
}
