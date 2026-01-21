<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Utils\Util;

class ModeloController extends Controller
{
    public function __construct(Modelo $modelo) {
        $this->modelo = $modelo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->modelo->all();
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
        $request->validate($this->modelo->rules(), $this->modelo->feedback());
        $path = $request->file('imagem')->store('imagens/modelos', 'public');
        $data = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $path ?? null,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs,
        ]);
        return response()->json(['msg' => 'Salvo Com Sucesso!', 'data' => $data], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = $this->modelo->find($id);
        if (empty($data)) {
            return response()->json(['msg' => 'Não Encontrado'], 404);
        }
        return response()->json([$data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Modelo $modelo)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $this->modelo->find($id);
        if (empty($data)) {
            return response()->json(['msg' => 'Não Encontrado'], 404);
        }
        if ($request->method() === Util::PATCH) {
            $request->validate(Util::regrasDinamicas($request, $this->modelo), $this->modelo->feedback());
        }
        if ($request->method() === Util::PUT) {
            $request->validate($this->modelo->rules(), $this->modelo->feedback());
        }
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($data->imagem);
        }
        $path = $request->file('imagem')->store('imagens/modelos', 'public');
        $data->update([
            'nome' => $request->nome ?? $data->nome,
            'imagem' => $path ?? null,
            'marca_id' => $request->marca_id ?? $data->marca_id,
            'numero_portas' => $request->numero_portas ?? $data->numero_portas,
            'lugares' => $request->lugares ?? $data->lugares,
            'air_bag' => $request->air_bag ?? $data->air_bag,
            'abs' => $request->abs ?? $data->abs
        ]);
        return response()->json(['msg' => 'Atualizado Com Sucesso!', 'data' => $data], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = $this->modelo->find($id);
        if (empty($data)) {
            return response()->json(['msg' => 'Não Encontrado'], 404);
        }

        Storage::disk('public')->delete($data->imagem);

        $data->delete();
        return response()->json(['msg' => 'Deletado Com Sucesso!'], 200);
    }
}
