<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Attention;
use App\Models\LateTemperature;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LateTemperatureController extends Controller
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
        $validated = $request->validate([
            'attention_id' => 'required|integer|unique:late_temperatures,attention_id',
            'value' => 'required|numeric|between:30.0,42.9'
        ]);

        $currentAttention = Attention::where('id', $validated['attention_id'])
            ->whereDate('created_at', date('Y-m-d'))
            ->first();

        if (date('H') < 12 || !$currentAttention) {
            return response([
                'message' => 'Error al guardar la temperatura'
            ], 401);
        }

        return response([
            'message' => 'Temperatura guardada correctamente',
            'data' =>  LateTemperature::create($validated)
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
        $validated = $request->validate([
            'value' => 'required|numeric|between:30.0,42.9'
        ]);

        $temp = LateTemperature::where('id', $id)
            ->whereHas('attention', function (Builder $query) {
                $query->whereDate('created_at', date('Y-m-d'));
            })->first();

        if (date('H') < 12 || !$temp) {
            return response([
                'message' => 'Error al actualizar la temperatura'
            ], 401);
        }
        $temp->value = $validated['value'];
        $temp->save();

        return response([
            'message' => 'Temperatura guardada correctamente',
            'data' => $temp
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
        $temp = LateTemperature::where('id', $id)
            ->whereHas('attention', function (Builder $query) {
                $query->where(DB::raw('date(created_at)'), date('Y-m-d'));
            })->first();

        if(!$temp) {
            return response([
                'message' => 'Error al eliminar la  temperatura'
            ], 401);
        }
        $temp->delete();

        return response(['message' => 'Temperatura eliminada correctamente']);
    }
}
