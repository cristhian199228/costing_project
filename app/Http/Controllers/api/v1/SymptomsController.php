<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Attention;
use App\Models\Symptom;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SymptomsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validated =$request->validate([
            'attention_id' => 'required|integer|unique:symptoms,attention_id',
        ]);

        $currentAttention = Attention::where('id', $validated['attention_id'])
            ->whereDate('created_at', date('Y-m-d'))
            ->first();

        if (!$currentAttention) {
            return response([
                'message' => 'Error al guardar los síntomas'
            ], 401);
        }

        return response([
            'message' => 'Sintomas creados correctamente',
            'data' => Symptom::create($request->all())
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $symptoms = Symptom::where('id', $id)
            ->whereHas('attention', function (Builder $query) {
                $query->where(DB::raw('date(created_at)'), date('Y-m-d'));
            })->first();

        if(!$symptoms) {
            return response([
                'message' => 'Error al actualizar la temperatura'
            ], 401);
        }
        $data = $request->except('id', 'created_at', 'updated_at', 'attention_id');

        $symptoms->update($data);

        return response([
            'message' => 'Síntomas actualizados correctamente',
            'data' => Symptom::findOrFail($id)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $sintomas = Symptom::where('id', $id)
            ->whereHas('attention', function (Builder $query) {
                $query->where(DB::raw('date(created_at)'), date('Y-m-d'));
            })->first();

        if(!$sintomas) {
            return response([
                'message' => 'Error al eliminar la  temperatura'
            ], 401);
        }
        $sintomas->delete();

        return response([
            'message' => 'Síntomas eliminados correctamente'
        ]);
    }
}
