<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class ModeloRepository {
    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function selectAtributosMarca($atributos)
    {
        $this->model = $this->model->with($atributos);
    }

    public function selectAtributosModelo($atributos)
    {
        $this->model = $this->model->selectRaw($atributos);
    }

    public function filtroWhere($atributos) {
        $filtro = explode(';', $atributos);
        foreach ($filtro as $key => $condicao) {
            $c = explode(':', $condicao);
        }
        $this->model = $this->model->where($c[0], $c[1],  $c[2]);
    }

    public function getResultado()
    {
        return $this->model->get();
    }
}
