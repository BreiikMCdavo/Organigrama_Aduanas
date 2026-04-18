<?php

namespace App\Http\Controllers;

use App\Models\ServidorPublico;
use Illuminate\Http\Request;

class ServidorPublicoController extends Controller
{
    public function index()
    {
        $servidores = ServidorPublico::all();
        return view('servidores.index', compact('servidores'));
    }

    public function create()
    {
        return view('servidores.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        
        if ($request->hasFile('fotografia')) {
            $path = $request->file('fotografia')->store('servidores', 'public');
            $data['fotografia'] = $path;
        }
        
        ServidorPublico::create($data);
        
        return redirect()->route('servidores.index')->with('success', 'Servidor registrado correctamente');
    }

    public function show($id)
    {
        $servidor = ServidorPublico::findOrFail($id);
        return view('servidores.show', compact('servidor'));
    }

    public function edit($id)
    {
        $servidor = ServidorPublico::findOrFail($id);
        return view('servidores.edit', compact('servidor'));
    }

    public function update(Request $request, $id)
    {
        $servidor = ServidorPublico::findOrFail($id);
        $data = $request->all();
        
        if ($request->hasFile('fotografia')) {
            $path = $request->file('fotografia')->store('servidores', 'public');
            $data['fotografia'] = $path;
        }
        
        $servidor->update($data);
        
        return redirect()->route('servidores.index')->with('success', 'Actualizado correctamente');
    }

    public function destroy($id)
    {
        ServidorPublico::destroy($id);
        return redirect()->route('servidores.index')->with('success', 'Eliminado correctamente');
    }
}