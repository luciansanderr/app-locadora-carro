<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Carro extends Model
{
    // /** @use HasFactory<\Database\Factories\CarroFactory> */
    use HasFactory;

    protected $fillable = ['id', 'modelo_id', 'placa', 'disponivel', 'km'];

    public function rules() {
        return [
            'nome' => [
                'required',
                'min:3',
                Rule::unique('carros', 'placa')->ignore($this->id)
            ],
            //'imagem' => 'required|file|mimes:png|max:2048',
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório!',
            'nome.unique' => 'O nome da marca já existe!',
            'nome.min' => 'O nome da marca deve ter no mínimo 3 caracteres!',
        ];
    }

    public function modelo() {
        return $this->belongsTo(Modelo::class);
    }
}
