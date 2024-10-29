<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nome'
    ];

    public function rules() {
        return [
            'nome' => 'required'
        ];
    }

    public function carros()
    {
        return $this->belongsToMany(Carro::class, 'locacoes', 'cliente_id', 'carro_id')
            ->withPivot('id','cliente_id', 'carro_id');
    }

    public function locacoes(){
        return $this->hasMany(Locacao::class, 'cliente_id', 'id');
    }

}
