<?php

namespace App\Http\Controllers;

use App\Models\modelcrud;
use Illuminate\Http\Request;

class crud extends Controller
{
    public function index()
    {
        $datapengguna = modelcrud::all();
        return view('welcome')->with([
            'datapengguna' => $datapengguna
        ]);;
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:datapengguna,email',
        ]);

        $datapengguna = [
            'nama' => $request->nama,
            'email' => $request->email,
        ];

        modelcrud::create($datapengguna);
        $datapengguna = modelcrud::all();

        return view('welcome')->with([
            'datapengguna' => $datapengguna
        ]);
    }

    public function show()
    {
        $datapengguna = modelcrud::all();
        return view('welcome')->with([
            'datapengguna' => $datapengguna
        ]);
    }

    public function edit($id)
    {
        $datapengguna = modelcrud::findOrFail($id);
        return response()->json($datapengguna);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $datapengguna = modelcrud::findOrFail($id);
        $datapengguna->nama = $request->nama;
        $datapengguna->email = $request->email;
        $datapengguna->save();
    }

    public function destroy($id)
    {
        $datapengguna = modelcrud::findOrFail($id);
        $datapengguna->delete();

        return view('welcome')->with([
            'datapengguna' => $datapengguna
        ]);
    }
}
