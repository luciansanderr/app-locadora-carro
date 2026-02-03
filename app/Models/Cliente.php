<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Cliente extends Model
{
    /** @use HasFactory<\Database\Factories\ClienteFactory> */
    use HasFactory;

    protected $fillable = ['id', 'nome'];

    public function rules() {
        return [
            'nome' => [
                'required',
                'min:3',
                Rule::unique('clientes', 'nome')->ignore($this->id)
            ]
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
}
