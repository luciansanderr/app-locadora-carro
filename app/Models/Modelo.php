<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Modelo extends Model
{
    protected $fillable = ['marca_id', 'id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function rules() {
        return [
            'marca_id' => 'required|exists:marcas,id',
            'nome' => [
                'required',
                'min:3',
                Rule::unique('modelos', 'nome')->ignore($this->id)
            ],
            'imagem' => 'required|file|mimes:png|max:2048',
            'numero_portas'=> 'required|integer|digits_between:1,5',
            'lugares'=> 'required|integer|digits_between:1,9',
            'air_bag'=> 'required|boolean',
            'abs'=> 'required|boolean',
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório!',
            // 'nome.unique' => 'O nome da marca já existe!',
            // 'nome.min' => 'O nome da marca deve ter no mínimo 3 caracteres!',
            // 'imagem.mimes' => 'A imagem deve ser do tipo PNG!',
            // 'imagem.max' => 'A imagem deve ter no máximo 2MB!',
            // 'imagem.file' => 'A imagem deve ser um arquivo válido!',
        ];
    }

    public function marca() {
        return $this->belongsTo(Marca::class);
    }
}
