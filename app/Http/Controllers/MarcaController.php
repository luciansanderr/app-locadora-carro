<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $path = $request->file('imagem')->store('imagens', 'public');
        $data = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $path ?? null,
        ]);
        return response()->json(['msg' => 'Salvo Com Sucesso!', 'data' => $data], 200);
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
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($data->imagem);
        }
        $path = $request->file('imagem')->store('imagens', 'public');
        $data->update([
            'nome' => $request->nome ?? $data->nome,
            'imagem' => $path ?? null,
        ]);
        return response()->json(['msg' => 'Atualizado Com Sucesso!', 'data' => $data], 200);
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

        Storage::disk('public')->delete($data->imagem);

        $data->delete();
        return response()->json(['msg' => 'Deletado Com Sucesso!'], 200);
    }

    public function baixarImagem($id)
    {
        $data = $this->marca->find($id);
        if (empty($data) || !$data->imagem) {
            return response()->json(['msg' => 'Não Encontrado'], 404);
        }
        $path = storage_path('app/public/' . $data->imagem);
        if (!file_exists($path)) {
            return response()->json(['msg' => 'Imagem Não Encontrada'], 404);
        }
        return response()->download($path);
    }
}
