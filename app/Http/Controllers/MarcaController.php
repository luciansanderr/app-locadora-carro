<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Utils\Util;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    // Injeção do Model
    public function __construct(Marca $marca) {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$data = Marca::all();
        $data = $this->marca->all();
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
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $this->marca->create($request->all());
        return response()->json(['msg' => 'Salvo Com Sucesso!'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //$data = Marca::find($marca->id);
        $data = $this->marca->find($id);
        if (empty($data)) {
            return response()->json(['msg' => 'Não Encontrado'], 404);
        }
        return response()->json([$data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Marca $marca)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //$marca->update($request->all());
        $data = $this->marca->find($id);
        if (empty($data)) {
            return response()->json(['msg' => 'Não Encontrado'], 404);
        }
        if ($request->method() === Util::PATCH) {
            $request->validate(Util::regrasDinamicas($request, $this->marca), $this->marca->feedback());
        }
        if ($request->method() === Util::PUT) {
            $request->validate($this->marca->rules(), $this->marca->feedback());
        }
        $data->update($request->all());
        return response()->json(['msg' => 'Atualizado Com Sucesso!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //$marca->delete();
        $data = $this->marca->find($id);
        if (empty($data)) {
            return response()->json(['msg' => 'Não Encontrado'], 404);
        }
        $data->delete();
        return response()->json(['msg' => 'Deletado Com Sucesso!'], 200);
    }
}
