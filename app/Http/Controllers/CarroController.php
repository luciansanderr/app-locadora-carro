<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarroRequest;
use App\Http\Requests\UpdateCarroRequest;
use App\Models\Carro;
use Illuminate\Http\Request;
use App\Repositories\CarroRepository;
use App\Utils\Util;

class CarroController extends Controller
{
    public function __construct(Carro $carro) {
        $this->carro = $carro;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carro);
        //$data = carro::all();
        $atributos = [];
        $atributosModelo = [];

        if ($request->has('atributos_modelo')) {
            $atributosModelo = "modelo:id,". $request->atributos_modelo;
            $carroRepository->selectAtributosRelacionados($atributosModelo);
        }

        if (!$request->has('atributos_modelo')) {
            $carroRepository->selectAtributosRelacionados('modelo');
        }

        if ($request->has('atributos')) {
            $atributos = $request->atributos;
            $carroRepository->selectAtributos($atributos);
        }

        if ($request->has('filtro')) {
            $atributos = $request->filtro;
            $carroRepository->filtroWhere($atributos);
        }

        $data = $carroRepository->getResultado();

        if (empty($data)) {
            return response()->json(['msg' => 'Não Encontrado'], 404);
        }
        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->carro->rules(), $this->carro->feedback());
        try {
            $data = $this->carro->create([
                'modelo_id' => $request->modelo_id,
                'placa' => $request->placa,
                'disponivel' => $request->disponivel,
                'km' => $request->km
            ]);
        } catch (\Throwable $th) {
            return response()->json(['msg' => 'Ocorreu um Erro!', 'data' => $th->getMessage()], 404);
        }

        return response()->json(['msg' => 'Salvo Com Sucesso!', 'data' => $data], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = $this->carro->with('modelo')->find($id);
        if (empty($data)) {
            return response()->json(['msg' => 'Não Encontrado'], 404);
        }
        return response()->json([$data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Carro $carro)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $this->carro->find($id);
        if (empty($data)) {
            return response()->json(['msg' => 'Não Encontrado'], 404);
        }
        if ($request->method() === Util::PATCH) {
            $request->validate(Util::regrasDinamicas($request, $this->carro));
        }
        if ($request->method() === Util::PUT) {
            $request->validate($this->carro->rules());
        }
        $data->update([
            'modelo_id' => $request->modelo_id ?? $data->modelo_id,
            'placa' => $request->placa ?? $data->placa,
            'disponivel' => $request->disponivel ?? $data->disponivel,
            'km' => $request->km ?? $data->km
        ]);
        return response()->json(['msg' => 'Atualizado Com Sucesso!', 'data' => $data], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = $this->carro->find($id);
        if (empty($data)) {
            return response()->json(['msg' => 'Não Encontrado'], 404);
        }
        $data->delete();
        return response()->json(['msg' => 'Deletado Com Sucesso!'], 200);
    }
}
