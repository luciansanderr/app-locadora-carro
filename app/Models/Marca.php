<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Marca extends Model
{
    protected $fillable = ['nome', 'imagem'];

    public function rules() {
        return [
            'nome' => [
                'required',
                'min:3',
                Rule::unique('marcas', 'nome')->ignore($this->id)
            ],
            'imagem' => 'required|max:100'
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório!',
            'nome.unique' => 'O nome da marca já existe!',
            'nome.min' => 'O nome da marca deve ter no mínimo 3 caracteres!',
        ];
    }
}
