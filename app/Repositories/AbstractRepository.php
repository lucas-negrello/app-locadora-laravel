<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAtributosRegistrosRelacionados($atributos)
    {
        $this->model = $this->model->with($atributos);
    }

    public function selectAtributosRegistrosRelacionadosRaw($atributos)
    {
        $this->model = $this->model->selectRaw('id,'.$atributos);
    }

    public function filtro($filtro)
    {
        $filtros = explode(';', $filtro);
        foreach($filtros as $key => $condicao){
            $condicoes = explode(':', $condicao);
            $this->model = $this->model->where($condicoes[0], $condicoes[1], $condicoes[2]);

        }
    }

    public function getResult()
    {
        return $this->model->get();
    }
}
