<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Presupuesto;
use App\Models\Tracing;
use App\Models\Planilla;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TracingController extends Controller
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
        $estado = $request->estado;
        $buscar = $request->buscar;

        $tracings = Presupuesto::whereBetween(DB::raw('date(created_at)'), [$request->fecha_inicio, $request->fecha_fin])
            ->when(!is_null($estado), function (Builder $q) use ($estado) {
                return $q->where('estado', $estado);
            })           
            ->orderBy('created_at')
            ->paginate($pages);

        $currentPage = $tracings->currentPage();
        $total = $tracings->total();
        $counter = $total - ($pages * ($currentPage - 1));
        
        foreach ($tracings as $tracing) {
           
            $tracing->date = $tracing->created_at->format('d/m/Y');
            $counter--;
        }

        return response($tracings);
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
            'patient_id' => 'required|integer',
            'days' => 'required|integer|between:1,30'
        ]);

        $currentTracing = Tracing::where('patient_id', $validated['patient_id'])
            ->where('status', 0)
            ->first();

        if ($currentTracing) {
            return response([
                'message' => 'El paciente tiene un seguimiento sin finalizar'
            ], 401);
        }
        $tracing = new Tracing();
        $tracing->patient_id = $validated['patient_id'];
        $tracing->end_date_tracing = Carbon::now()->addDays($validated['days']);
        $tracing->save();

        return response([
            'message' => 'Seguimiento creado correctamente',
            'data' => $tracing,
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
        $tracing = Tracing::where('id', $id)
            ->with('patient')
            ->with(['binnacle' => function ($q) {
                $q->latest();
            }])->first();

        if (count($tracing->binnacle) > 0) {
            foreach ($tracing->binnacle as $binnacle) {
                $binnacle->datetime = $binnacle->created_at->format('d/m/Y H:i');
            }
        }

        return response($tracing);
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
        //
        $tracing = Tracing::findOrFail($id);
        $tracing->delete();

        return response(['message' => 'Seguimiento eliminado correctamente']);
    }
    public function tracingPlanilla(Request $request)
    {
        $pages = 15;
        $estado = 1;
        $buscar = $request->buscar;

        $tracings = Planilla::when(!is_null($estado), function (Builder $q) use ($estado) {
                return $q->where('estado', $estado);
            })           
            ->orderBy('created_at')
            ->paginate($pages);

        $currentPage = $tracings->currentPage();
        $total = $tracings->total();
        $counter = $total - ($pages * ($currentPage - 1));
        
        foreach ($tracings as $tracing) {
           
            $tracing->date = $tracing->created_at->format('d/m/Y');
            $counter--;
        }

        return response($tracings);
    }

    
}
