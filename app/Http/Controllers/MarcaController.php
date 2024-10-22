<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{

    public function __construct(Marca $marca){
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marcas = $this->marca->all();
        return response()->json($marcas);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $marca = $this->marca->create($request->all());
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);
        if($marca === null){
            return response()->json(['erro' => 'Nenhum registro encontrado'], 404);
        }
        return response()->json($marca);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);
        if($marca === null){
            return response()->json(['erro' => 'Nenhum registro encontrado'], 404);
        }
        $marca->update($request->all());
        return response()->json($marca);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if($marca === null){
            return response()->json(['erro' => 'Nenhum registro encontrado'], 404);
        }
        $marca->delete();
        return response()->json($marca);
    }
}
