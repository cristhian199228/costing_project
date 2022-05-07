<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Presupuesto;
use App\Models\Tracing;
use App\Models\Planilla;
use App\Models\ValoresPlanilla;
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
        /*$validated = $request->validate([
            'patient_id' => 'required|integer',
            'days' => 'required|integer|between:1,30'
        ]);*/

       
        $tracing = new Planilla;
        $tracing->puesto = $request->puesto;
        $tracing->sueldo = $request->sueldo;    
        $tracing->estado = 1;       
        $tracing->save();

        return response([
            'message' => 'Planilla Creada Correctamente',
            'data' => $tracing,
        ]);
    }
    public function guardarPresupuesto(Request $request)
    {
        //
        /*$validated = $request->validate([
            'patient_id' => 'required|integer',
            'days' => 'required|integer|between:1,30'
        ]);*/
       
        $tracing = new Presupuesto();
        $tracing->descripcion = $request->descripcion;
        $tracing->duracion = $request->duracion;    
        $tracing->estado = 0; 
        $tracing->id_pais = 1;       
        $tracing->save();

        return response([
            'message' => 'Presupuesto Creado Correctamente',
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
        $tracing = Presupuesto::where('id', $id)
            ->with('planilla')
            /*->with(['binnacle' => function ($q) {
                $q->latest();
            }])*/
            ->first();

        return response($tracing);
    }
    public function valoresPlanilla()
    {
        //
        $tracing = ValoresPlanilla::All();

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
    public function eliminar(Request $request)
    {
        //
        $tracing = Planilla::findOrFail($request->id);
        $tracing->delete();

        return response(['message' => 'Planilla eliminado correctamente']);
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
