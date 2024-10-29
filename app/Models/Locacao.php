<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    protected $table = 'locacoes';

    protected $fillable = [
        'cliente_id',
        'carro_id',
        'data_inicio_periodo',
        'data_final_previsto_periodo',
        'data_final_realizado_periodo',
        'valor_diaria',
        'km_inicial',
        'km_final',
    ];

    public function rules() {
        return [
            'cliente_id' => 'required|integer|exists:clientes,id',
            'carro_id' => 'required|integer|exists:carros,id',
            'data_inicio_periodo' => 'required',
            'data_final_previsto_periodo' => 'required',
            'data_final_realizado_periodo' => 'required',
            'valor_diaria' => 'required|numeric',
            'km_inicial' => 'required|numeric',
            'km_final' => 'required|numeric',
        ];
    }

    public function carros()
    {
        return $this->belongsTo(Carro::class, 'carro_id', 'id');
    }
    public function clientes()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }


}
