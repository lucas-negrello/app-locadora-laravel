<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;

class CarroController extends Controller
{

    public function __construct(Carro $carro){
        $this->carro = $carro;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carro);

        if($request->has('atributos_modelo')){
            $atributos_modelo = $request->atributos_modelo;
            $atributos_modelo = 'modelo:id,'.$atributos_modelo;
            $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelo);
        }
        else{
            $carroRepository->selectAtributosRegistrosRelacionados('modelo');
        }
        if($request->has('atributos_clientes')){
            $atributos_clientes = $request->atributos_clientes;
            $atributos_clientes = 'clientes:id,'.$atributos_clientes;
            $carroRepository->selectAtributosRegistrosRelacionados($atributos_clientes);
        }
        else{
            $carroRepository->selectAtributosRegistrosRelacionados('clientes');
        }
        if($request->has('atributos_locacoes')){
            $atributos_locacoes = $request->atributos_locacoes;
            $atributos_locacoes = 'locacoes:id,'.$atributos_locacoes;
            $carroRepository->selectAtributosRegistrosRelacionados($atributos_locacoes);
        }
        else{
            $carroRepository->selectAtributosRegistrosRelacionados('locacoes');
        }
        if($request->has('filtro')){
            $carroRepository->filtro($request->filtro);
        }
        if($request->has('atributos')){
            $carroRepository->selectAtributosRegistrosRelacionadosRaw($request->atributos);
        }

        return response()->json($carroRepository->getResult());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->carro->rules());
        $carro = $this->carro->create([
            'modelo_id' => $request->modelo_id,
            'placa' => $request->placa,
            'disponivel' => $request->disponivel,
            'km' => $request->km,
        ]);
        return response()->json($carro, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $carro = $this->carro->with(['modelo', 'clientes', 'locacoes'])->find($id);
        if($carro === null){
            return response()->json(['erro' => 'Nenhum registro encontrado'], 404);
        }
        return response()->json($carro);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $carro = $this->carro->find($id);
        if($carro === null){
            return response()->json(['erro' => 'Nenhum registro encontrado'], 404);
        }
        if($request->method() === 'PATCH'){
            $dinamicRules = array();
            foreach ($carro->rules() as $input => $rule){
                if(array_key_exists($input, $request->all())){
                    $dinamicRules[$input] = $rule;
                }
            }
            $request->validate($dinamicRules);
        }else{
            $request->validate($carro->rules());
        }

        $request->validate($this->carro->rules());

        $carro->fill($request->all());
        $carro->save();

        return response()->json($carro);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);
        if($carro === null){
            return response()->json(['erro' => 'Nenhum registro encontrado'], 404);
        }
        $carro->delete();
        return response()->json($carro);
    }
}
