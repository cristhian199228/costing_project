<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Binnacle;
use Illuminate\Http\Request;

class BinnacleController extends Controller
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
            'tracing_id' => 'required|integer',
            'comment' => 'required|string'
        ]);

        return response([
            'message' => 'Comentario guardado correctamente',
            'data' => Binnacle::create($validated)
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
            'comment' => 'required|string'
        ]);
        $binnacle = Binnacle::findOrFail($id);
        $binnacle->comment = $validated['comment'];
        $binnacle->save();

        return response([
            'message' => 'Comentario actualizado correctamente',
            'data' => $binnacle
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
        $binnacle = Binnacle::findOrFail($id);
        $binnacle->delete();

        return response(['message' => 'Comentario eliminado correctamente']);
    }
}
