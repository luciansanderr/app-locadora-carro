<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocacaoRequest;
use App\Http\Requests\UpdateLocacaoRequest;
use App\Models\Locacoes;
use Illuminate\Http\Request;
use App\Utils\Util;
use App\Repositories\LocacaoRepository;

class LocacaoController extends Controller
{
    public function __construct(Locacoes $locacao)
    {
        $this->locacao = $locacao;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);
        //$data = Marca::all();
        $atributos = [];
        $atributosModelo = [];

        // if ($request->has('atributos_modelo')) {
        //     $atributosModelo = "modelo:marca_id,". $request->atributos_modelo;
        //     $locacaoRepository->selectAtributosRelacionados($atributosModelo);
        // }

        // if (!$request->has('atributos_modelo')) {
        //     $locacaoRepository->selectAtributosRelacionados('modelo');
        // }

        if ($request->has('atributos')) {
            $atributos = $request->atributos;
            $locacaoRepository->selectAtributos($atributos);
        }

        if ($request->has('filtro')) {
            $atributos = $request->filtro;
            $locacaoRepository->filtroWhere($atributos);
        }

        $data = $locacaoRepository->getResultado();

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
        $request->validate($this->locacao->rules());
        try {
            $data = $this->locacao->create([
                'cliente_id' => $request->cliente_id,
                'carro_id' => $request->carro_id,
                'data_inicio_periodo' => $request->data_inicio_periodo,
                'data_final_previsto_periodo' => $request->data_final_previsto_periodo,
                'data_final_realizado_periodo' => $request->data_final_realizado_periodo,
                'valor_diaria' => $request->valor_diaria,
                'km_inicial' => $request->km_inicial,
                'km_final' => $request->km_final
            ]);
        } catch (\Throwable $th) {
            return response()->json(['msg' => 'Ocorreu um Erro!', 'data' => $th->getMessage()], 200);
        }

        return response()->json(['msg' => 'Salvo Com Sucesso!', 'data' => $data], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = $this->locacao->find($id);
        if (empty($data)) {
            return response()->json(['msg' => 'Não Encontrado'], 404);
        }
        return response()->json([$data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Locacao $locacao)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $this->locacao->find($id);
        if (empty($data)) {
            return response()->json(['msg' => 'Não Encontrado'], 404);
        }
        if ($request->method() === Util::PATCH) {
            $request->validate(Util::regrasDinamicas($request, $this->locacao), $this->locacao->feedback());
        }
        if ($request->method() === Util::PUT) {
            $request->validate($this->locacao->rules(), $this->locacao->feedback());
        }
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
        $data = $this->locacao->find($id);

        if (empty($data)) {
            return response()->json(['msg' => 'Não Encontrado'], 404);
        }

        $data->delete();
        return response()->json(['msg' => 'Deletado Com Sucesso!'], 200);
    }
}
