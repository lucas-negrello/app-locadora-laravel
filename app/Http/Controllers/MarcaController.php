<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{

    public function __construct(Marca $marca){
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca);

        if($request->has('atributos_modelos')){
            $atributos_modelos = $request->atributos_modelos;
            $atributos_modelos = 'modelos:id,marca_id,'.$atributos_modelos;
            $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        }
        else{
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
        }
        if($request->has('filtro')){
            $marcaRepository->filtro($request->filtro);
        }
        if($request->has('atributos')){
            $marcaRepository->selectAtributosRegistrosRelacionadosRaw($request->atributos);
        }

        return response()->json([
            'status' =>  'success',
            'data' => $marcaRepository->getResult(),
            'message' => 'Lista de Marcas',
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate($this->marca->rules(), $this->marca->feedback());
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');
        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
        ]);
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);
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
        if($request->method() === 'PATCH'){
            $dinamicRules = array();
            foreach ($marca->rules() as $input => $rule){
                if(array_key_exists($input, $request->all())){
                    $dinamicRules[$input] = $rule;
                }
            }
            $request->validate($dinamicRules, $marca->feedback());
        }else{
            $request->validate($marca->rules(), $marca->feedback());
        }

        if($request->file('imagem')){
            Storage::disk('public')->delete($marca->imagem);
        }

        $request->validate($this->marca->rules(), $this->marca->feedback());
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');

        $marca->fill($request->all());
        $marca->imagem = $imagem_urn;
        $marca->save();
        /*
        $marca->update([
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
        ]);
        */

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
        Storage::disk('public')->delete($marca->imagem);
        $marca->delete();
        return response()->json($marca);
    }
}
