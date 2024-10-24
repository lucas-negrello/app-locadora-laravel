<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'imagem'];

    public function rules() {
        return [
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:2',
            'imagem' => 'required|file|mimes:jpeg,jpg,png',
            ];
    }
    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'A marca já está registrada',
            'nome.min' => 'O nome deve ter ao menos 2 caracteres',
            'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo png, jpeg ou jpg'
        ];
    }
}
