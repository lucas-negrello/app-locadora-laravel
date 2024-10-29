<?php

namespace App\Http\Controllers;


use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use Illuminate\Http\Request;

class ClienteController extends Controller
{

    public function __construct(Cliente $cliente){
        $this->cliente = $cliente;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);

        if($request->has('atributos_carros')){
            $atributos_carros = $request->atributos_carros;
            $atributos_carros = 'carros:id,'.$atributos_carros;
            $clienteRepository->selectAtributosRegistrosRelacionados($atributos_carros);
        }
        else{
            $clienteRepository->selectAtributosRegistrosRelacionados('carros');
        }
        if($request->has('atributos_locacoes')){
            $atributos_locacoes = $request->atributos_locacoes;
            $atributos_locacoes = 'locacoes:id,'.$atributos_locacoes;
            $clienteRepository->selectAtributosRegistrosRelacionados($atributos_locacoes);
        }
        else{
            $clienteRepository->selectAtributosRegistrosRelacionados('locacoes');
        }
        if($request->has('filtro')){
            $clienteRepository->filtro($request->filtro);
        }
        if($request->has('atributos')){
            $clienteRepository->selectAtributosRegistrosRelacionadosRaw($request->atributos);
        }

        return response()->json($clienteRepository->getResult());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->cliente->rules());
        $cliente = $this->cliente->create([
            'nome' => $request->nome,
        ]);
        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cliente = $this->cliente->with(['carros', 'locacoes'])->find($id);
        if($cliente === null){
            return response()->json(['erro' => 'Nenhum registro encontrado'], 404);
        }
        return response()->json($cliente);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cliente = $this->cliente->find($id);
        if($cliente === null){
            return response()->json(['erro' => 'Nenhum registro encontrado'], 404);
        }
        if($request->method() === 'PATCH'){
            $dinamicRules = array();
            foreach ($cliente->rules() as $input => $rule){
                if(array_key_exists($input, $request->all())){
                    $dinamicRules[$input] = $rule;
                }
            }
            $request->validate($dinamicRules);
        }else{
            $request->validate($cliente->rules());
        }

        $request->validate($this->cliente->rules());

        $cliente->fill($request->all());
        $cliente->save();

        return response()->json($cliente);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);
        if($cliente === null){
            return response()->json(['erro' => 'Nenhum registro encontrado'], 404);
        }
        $cliente->delete();
        return response()->json($cliente);
    }
}
