<?php

namespace App\Http\Controllers;

use App\Models\ServidorPublico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServidorPublicoController extends Controller
{
    public function index(Request $request)
    {
        $area = $request->area;

        if ($area) {

            if ($area === 'GERENCIA REGIONAL LA PAZ - GRLPZ') {

                // Mostrar todos
                $servidores = ServidorPublico::orderBy('created_at', 'desc')->get();

            } else {

                // Filtrar por unidad o sub_unidad
                $servidores = ServidorPublico::where('unidad', $area)
                    ->orWhere('sub_unidad', $area)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

        } else {

            // Mostrar todos
            $servidores = ServidorPublico::orderBy('created_at', 'desc')->get();

        }

        return view('servidores.index', compact('servidores'));
    }

    public function create()
    {
        return view('servidores.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'tipo' => 'required|in:item,consultoria',
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'fotografia' => 'nullable|image|max:2048',
        ];

        if ($request->tipo === 'item') {
            $rules['numero_item'] = 'required|string|max:50';
            $rules['fecha_ingreso_aduana'] = 'nullable|date';
            $rules['fecha_inicio_cargo'] = 'nullable|date';
        } else {
            $rules['contrato_numero'] = 'required|string|max:100';
            $rules['fecha_ingreso_aduana'] = 'nullable|date';
            $rules['fecha_inicio_contrato'] = 'nullable|date';
            $rules['fecha_fin_contrato'] = 'nullable|date';
        }

        $validated = $request->validate($rules);

        $data = $request->except('fotografia');

        if ($request->hasFile('fotografia')) {
            $data['fotografia'] = $request->file('fotografia')->store('servidores', 'public');
        }

        ServidorPublico::create($data);

        return redirect()->route('servidores.index')
            ->with('success', 'Servidor público registrado correctamente.');
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

        $rules = [
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'fotografia' => 'nullable|image|max:2048',
        ];

        if ($servidor->tipo === 'item') {
            $rules['numero_item'] = 'required|string|max:50';
            $rules['fecha_ingreso_aduana'] = 'nullable|date';
            $rules['fecha_inicio_cargo'] = 'nullable|date';
        } else {
            $rules['contrato_numero'] = 'required|string|max:100';
            $rules['fecha_ingreso_aduana'] = 'nullable|date';
            $rules['fecha_inicio_contrato'] = 'nullable|date';
            $rules['fecha_fin_contrato'] = 'nullable|date';
        }

        $request->validate($rules);

        $data = $request->except(['fotografia', '_token', '_method']);

        if ($request->hasFile('fotografia')) {
            // Eliminar foto anterior si existe
            if ($servidor->fotografia) {
                Storage::disk('public')->delete($servidor->fotografia);
            }
            $data['fotografia'] = $request->file('fotografia')->store('servidores', 'public');
        }

        $servidor->update($data);

        return redirect()->route('servidores.show', $servidor->id)
            ->with('success', 'Servidor actualizado correctamente.');
    }

    public function destroy($id)
    {
        $servidor = ServidorPublico::findOrFail($id);

        if ($servidor->fotografia) {
            Storage::disk('public')->delete($servidor->fotografia);
        }

        $servidor->delete();

        return redirect()->route('servidores.index')
            ->with('success', 'Servidor eliminado correctamente.');
    }
}
