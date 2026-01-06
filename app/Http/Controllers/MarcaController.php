<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Marca::all();

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
        Marca::create($request->all());

        return response()->json([
            'msg' => 'Salvo Com Sucesso!'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Marca $marca)
    {
        $data = Marca::find($marca->id);

        return response()->json([
            $data
        ], 200);
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
    public function update(Request $request, Marca $marca)
    {
        $marca->update($request->all());

        return response()->json([
            'msg' => 'Atualizado Com Sucesso!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marca $marca)
    {
        $marca->delete();

        return response()->json([
            'msg' => 'Deletado Com Sucesso!'
        ], 200);
    }
}
