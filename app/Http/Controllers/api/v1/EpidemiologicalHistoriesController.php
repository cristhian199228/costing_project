<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Attention;
use App\Models\EpidemiologicalHistory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EpidemiologicalHistoriesController extends Controller
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
            'attention_id' => 'required|integer|unique:epidemiological_histories,attention_id',
        ]);

        $currentDayAttention = Attention::where('id', $validated['attention_id'])
            ->whereDate('created_at', date('Y-m-d'))
            ->first();

        if (!$currentDayAttention) {
            return response([
                'message' => 'Error al guardar los antecedentes epidemiológicos'
            ], 401);
        }

        return response([
            'message' => 'Antecedentes epidemiológicos creados correctamente',
            'data' => EpidemiologicalHistory::create($request->all())
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
        $eh = EpidemiologicalHistory::where('id', $id)
            ->whereHas('attention', function (Builder $query) {
                $query->whereDate('created_at', date('Y-m-d'));
            })->first();

        if(!$eh) {
            return response([
                'message' => 'Error al actualizar los antecedentes'
            ], 401);
        }
        $data = $request->except('id', 'created_at', 'updated_at', 'attention_id');

        $eh->update($data);

        return response([
            'message' => 'Antecedentes epidemiológicos actualizados correctamente',
            'data' => EpidemiologicalHistory::findOrFail($id)
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

        $eh = EpidemiologicalHistory::where('id', $id)
            ->whereHas('attention', function (Builder $query) {
                $query->whereDate('created_at', date('Y-m-d'));
            })->first();

        if(!$eh) {
            return response([
                'message' => 'Error al eliminar los antecedentes'
            ], 401);
        }
        $eh->delete();

        return response([
            'message' => 'Antecedentes epidemiológicos eliminados correctamente'
        ]);
    }
}
