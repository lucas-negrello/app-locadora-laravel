<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{

    public function __construct(Modelo $modelo){
        $this->modelo = $modelo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $modelos = array();
        // atributos = id,nome,imagem
        if($request->has('atributos_marca')){
            $atributos_marca = $request->atributos_marca;
            $modelos = $this->modelo->with('marca:id,'.$atributos_marca);
        }
        else{
            $modelos = $this->modelo->with('marca');
        }
        if($request->has('atributos')){
            $atributos = $request->atributos;
            $modelos = $modelos->selectRaw('id,marca_id,'.$atributos)->get();
        } else {
            $modelos = $modelos->get();
        }

        return response()->json($modelos);
        // $modelo = $this->modelo->with('marca')->get();
        // return response()->json($modelo);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules());
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos', 'public');
        $modelo = $this->modelo->create([
            'marca_id' => $request->get('marca_id'),
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs,
        ]);
        return response()->json($modelo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        if($modelo === null){
            return response()->json(['erro' => 'Nenhum registro encontrado'], 404);
        }
        return response()->json($modelo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);
        if($modelo === null){
            return response()->json(['erro' => 'Nenhum registro encontrado'], 404);
        }
        if($request->method() === 'PATCH'){
            $dinamicRules = array();
            foreach ($modelo->rules() as $input => $rule){
                if(array_key_exists($input, $request->all())){
                    $dinamicRules[$input] = $rule;
                }
            }
            $request->validate($dinamicRules);
        }else{
            $request->validate($modelo->rules());
        }

        if($request->file('imagem')){
            Storage::disk('public')->delete($modelo->imagem);
        }

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos', 'public');

        $modelo->fill($request->all());
        $modelo->imagem = $imagem_urn;
        $modelo->save();

        /*
        $modelo->update([
            'marca_id' => $request->get('marca_id'),
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs,
        ]);
        */

        return response()->json($modelo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);
        if($modelo === null){
            return response()->json(['erro' => 'Nenhum registro encontrado'], 404);
        }
        Storage::disk('public')->delete($modelo->imagem);
        $modelo->delete();
        return response()->json($modelo);
    }
}
