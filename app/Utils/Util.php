<?php

namespace App\Utils;

class Util
{
// Coloque aqui métodos utilitários que podem ser usados em várias partes da aplicação
    const PATCH = "PATCH";
    const PUT = "PUT";

    public static function regrasDinamicas($request, $model) {
        $regras = [];

        foreach ($model->rules() as $input => $regra) {
            if (array_key_exists($input, $request->all())) {
                $regras[$input] = $regra;
            }
        }

        return $regras;
    }
}
