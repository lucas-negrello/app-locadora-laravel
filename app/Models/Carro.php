<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    protected $fillable = [
        'modelo_id',
        'placa',
        'disponivel',
        'km',
    ];

    public function rules() {
        return [
            'modelo_id' => 'exists:modelos,id',
            'placa' => 'required',
            'disponivel' => 'required',
            'km' => 'required',
        ];
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }

    public function clientes()
    {
        return $this->belongsToMany(Cliente::class, 'locacoes', 'carro_id', 'cliente_id')
            ->withPivot('carro_id', 'cliente_id','id');
    }

    public function locacoes(){
        return $this->hasMany(Locacao::class, 'carro_id', 'id');
    }
}
