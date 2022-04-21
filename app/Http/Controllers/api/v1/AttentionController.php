<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Attention;
use App\Models\Pais;
use App\Models\Tracing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AttentionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $pages = 15;

        $attentions = $this->getAttentions($request)
            ->paginate($pages);

        $currentPage = $attentions->currentPage();
        $total = $attentions->total();
        $counter = $total - ($pages * ($currentPage - 1));

        foreach ($attentions as $attention) {
            $pac = $attention->patient;
            $attention->date = $attention->created_at ? $attention->created_at->format('d/m/Y') : '';
            $attention->counter = $counter;
            $pac->full_name = Str::upper($pac->nombres . " " . $pac->apellido_paterno . " " . $pac->apellido_materno);
            //$pac->tracings = Tracing::where('patient_id', $pac->idpacientes)->where('status', 0)->count();
            $counter--;
        }

        return response($attentions);
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
            'idpacientes' => 'required|integer'
        ]);
        $created_attention = Attention::where('patient_id', $validated['idpacientes'])
            ->where(DB::raw('date(created_at)'), date('Y-m-d'))
            ->first();

        if($created_attention) {
            return response([
                'message' => 'El paciente tiene una atención creada en el día'
            ], 401);
        }
        $attention = new Attention();
        $attention->patient_id = $validated['idpacientes'];
        $attention->hash = date('Ymd') . $validated['idpacientes'];
        $attention->save();

        return response([
            'message' => 'Atencion creada correctamente',
            'data' => $attention
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
        $pages = 15;
        $attentions = Pais::latest()
            ->paginate($pages);

        $currentPage = $attentions->currentPage();
        $total = $attentions->total();
        $counter = $total - ($pages * ($currentPage - 1));

        foreach ($attentions as $attention) {
            $attention->date = $attention->created_at ? $attention->created_at->format('d/m/Y') : '';
            $attention->counter = $counter;
            $attention->current = $attention->created_at->format('Ymd') === date('Ymd');
            $counter--;
        }

        return response($attentions);

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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attention = Attention::findOrFail($id);
        $attention->delete();

        return response(['message' => 'Atención eliminada correctamente']);
    }

    public function getAttentions(Request $request) {
        $buscar = $request->buscar;
        $sintomas = $request->sintomas;
        $antedentes = $request->antecedentes;
        $et = $request->et;
        $lt = $request->lt;
        $dc = $request->dc;

        return Attention::whereBetween(DB::raw('date(created_at)'), [$request->fecha_inicio, $request->fecha_fin])
            ->whereHas('patient',function ($q) use ($buscar) {
                $q->search($buscar);
            })->when(!is_null($sintomas), function (Builder $q) use ($sintomas){
                if ($sintomas) return $q->has('symptoms');
                return $q->doesntHave('symptoms');
            })->when(!is_null($antedentes), function (Builder $q) use ($antedentes){
                if ($antedentes) return $q->has('epidemiologicalHistories');
                return $q->doesntHave('epidemiologicalHistories');
            })->when(!is_null($et), function (Builder $q) use ($et){
                if ($et) return $q->whereHas('earlyTemp', function (Builder $q) {
                    $q->where('value', '>=',37.8);
                });
                return $q->whereHas('earlyTemp', function (Builder $q) {
                    $q->where('value', '<',37.8);
                });
            })->when(!is_null($lt), function (Builder $q) use ($lt){
                if ($lt) return $q->whereHas('lateTemp', function (Builder $q) {
                    $q->where('value', '>=',37.8);
                });
                return $q->whereHas('lateTemp', function (Builder $q) {
                    $q->where('value', '<',37.8);
                });
            })->when(!is_null($dc), function (Builder $q) use ($dc){
                if ($dc) return $q->has('directContacts');
                return $q->doesntHave('directContacts');
            })
            ->with('earlyTemp', 'lateTemp', 'symptoms','patient','epidemiologicalHistories','directContacts')
            ->latest();
    }
}
