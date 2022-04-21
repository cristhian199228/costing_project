<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Attention;
use App\Models\DirectContact;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DirectContactsController extends Controller
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
        $request->validate([
            'attention_id' => 'required|integer',
            'full_name' => 'required|string',
        ]);

        return response([
            'message' => 'Contacto directo guardado correctamente',
            'data' => DirectContact::create($request->all())
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
        return DirectContact::where('attention_id', $id)
            ->get();
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
        $dc = DirectContact::findOrFail($id);
        $data = $request->except('id', 'created_at', 'updated_at', 'attention_id');
        $dc->update($data);

        return response([
            'message' => 'Contacto directo actualizado correctamente',
            'data' => $dc
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
        $dc =  DirectContact::findOrFail($id);
        $dc->delete();

        return response([
            'message' => 'Se eliminó el contacto directo correctamente'
        ]);
    }
}
