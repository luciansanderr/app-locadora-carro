<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Locacoes extends Model
{
    /** @use HasFactory<\Database\Factories\LocacaoFactory> */
    use HasFactory;

    protected $table = 'locacoes';
    protected $fillable = ['cliente_id', 'carro_id', 'data_inicio_periodo', 'data_final_previsto_periodo', 'data_final_realizado_periodo', 'valor_diaria', 'km_inicial', 'km_final'];

    public function rules() {
        return [
            // 'carro_id' => [
            //     'required',
            //     'min:3',
            //     Rule::unique('lotacoes', 'carro_id')->ignore($this->id)
            // ]
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
