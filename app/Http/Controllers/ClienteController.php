<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Utils\Util;

class ClienteController extends Controller
{
    public function __construct(Cliente $cliente) {
        $this->cliente = $cliente;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);
        //$data = carro::all();
        $atributos = [];
        $atributosModelo = [];

        // if ($request->has('atributos_modelo')) {
        //     $atributosModelo = "modelo:id,". $request->atributos_modelo;
        //     $clienteRepository->selectAtributosRelacionados($atributosModelo);
        // }

        // if (!$request->has('atributos_modelo')) {
        //     $clienteRepository->selectAtributosRelacionados('modelo');
        // }

        if ($request->has('atributos')) {
            $atributos = $request->atributos;
            $clienteRepository->selectAtributos($atributos);
        }

        if ($request->has('filtro')) {
            $atributos = $request->filtro;
            $clienteRepository->filtroWhere($atributos);
        }

        $data = $clienteRepository->getResultado();

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
        $request->validate($this->cliente->rules(), $this->cliente->feedback());
        try {
            $data = $this->cliente->create([
                'nome' => $request->nome
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
        $data = $this->cliente->find($id);
        if (empty($data)) {
            return response()->json(['msg' => 'Não Encontrado'], 404);
        }
        return response()->json([$data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Cliente $cliente)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $this->cliente->find($id);
        if (empty($data)) {
            return response()->json(['msg' => 'Não Encontrado'], 404);
        }
        if ($request->method() === Util::PATCH) {
            $request->validate(Util::regrasDinamicas($request, $this->cliente));
        }
        if ($request->method() === Util::PUT) {
            $request->validate($this->cliente->rules());
        }
        $data->update([
            'nome'=> $request->nome
        ]);
        return response()->json(['msg' => 'Atualizado Com Sucesso!', 'data' => $data], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = $this->cliente->find($id);
        if (empty($data)) {
            return response()->json(['msg' => 'Não Encontrado'], 404);
        }
        $data->delete();
        return response()->json(['msg' => 'Deletado Com Sucesso!'], 200);
    }
}
