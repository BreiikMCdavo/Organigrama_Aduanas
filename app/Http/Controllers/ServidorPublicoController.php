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
        $data = [];

        // 📸 Imagen
        if ($request->hasFile('fotografia')) {
            $path = $request->file('fotografia')->store('servidores', 'public');
            $data['fotografia'] = $path;
        }

        // ================= ITEM =================
        if ($request->tipo == 'item') {

            $data = array_merge($data, [
                'tipo' => 'item',

                'numero_item' => $request->numero_item,
                'cite_memorandum' => $request->cite_memorandum,
                'cargo' => $request->cargo,
                'designacion' => $request->designacion,

                'nombre' => $request->nombre,
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,

                'fecha_ingreso_aduana' => $request->fecha_ingreso_aduana,
                'fecha_inicio_cargo' => $request->fecha_inicio_cargo,

                'asignacion_familiar_desc' => $request->asignacion_familiar_desc,
                'asignacion_familiar_grado' => $request->asignacion_familiar_grado,

                'casos_especiales_desc' => $request->casos_especiales_desc,
                'casos_especiales_grado' => $request->casos_especiales_grado,

                'discapacidad_desc' => $request->discapacidad_desc,
                'discapacidad_grado' => $request->discapacidad_grado,
            ]);
        }

        // ================= CONSULTORIA =================
        if ($request->tipo == 'consultoria') {

            $data = array_merge($data, [
                'tipo' => 'consultoria',

                'contrato_numero' => $request->contrato_numero,
                'cargo_consultoria' => $request->cargo_consultoria,

                // ⚠️ IMPORTANTE: estos nombres deben existir en tu form
                'consultoria_nombre' => $request->consultoria_nombre,
                'consultoria_apellido_paterno' => $request->consultoria_apellido_paterno,
                'consultoria_apellido_materno' => $request->consultoria_apellido_materno,

                'fecha_ingreso_aduana' => $request->fecha_ingreso_aduana,
                'fecha_inicio_contrato' => $request->fecha_inicio_contrato,
                'fecha_fin_contrato' => $request->fecha_fin_contrato,

                'asignacion_familiar_desc' => $request->asignacion_familiar_desc,
                'asignacion_familiar_grado' => $request->asignacion_familiar_grado,

                'casos_especiales_desc' => $request->casos_especiales_desc,
                'casos_especiales_grado' => $request->casos_especiales_grado,

                'discapacidad_desc' => $request->discapacidad_desc,
                'discapacidad_grado' => $request->discapacidad_grado,
            ]);
        }

        ServidorPublico::create($data);

        return redirect()->route('servidores.index')
            ->with('success', 'Servidor registrado correctamente');
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
        $data = [];

        // 📸 Imagen
        if ($request->hasFile('fotografia')) {
            $path = $request->file('fotografia')->store('servidores', 'public');
            $data['fotografia'] = $path;
        }

        // Igual que store pero update
        if ($request->tipo == 'item') {
            $data = array_merge($data, [
                'tipo' => 'item',
                'numero_item' => $request->numero_item,
                'cite_memorandum' => $request->cite_memorandum,
                'cargo' => $request->cargo,
                'designacion' => $request->designacion,
                'nombre' => $request->nombre,
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,
                'fecha_ingreso_aduana' => $request->fecha_ingreso_aduana,
                'fecha_inicio_cargo' => $request->fecha_inicio_cargo,
                'asignacion_familiar_desc' => $request->asignacion_familiar_desc,
                'asignacion_familiar_grado' => $request->asignacion_familiar_grado,
                'casos_especiales_desc' => $request->casos_especiales_desc,
                'casos_especiales_grado' => $request->casos_especiales_grado,
                'discapacidad_desc' => $request->discapacidad_desc,
                'discapacidad_grado' => $request->discapacidad_grado,
            ]);
        }

        if ($request->tipo == 'consultoria') {
            $data = array_merge($data, [
                'tipo' => 'consultoria',
                'contrato_numero' => $request->contrato_numero,
                'cargo_consultoria' => $request->cargo_consultoria,
                'consultoria_nombre' => $request->consultoria_nombre,
                'consultoria_apellido_paterno' => $request->consultoria_apellido_paterno,
                'consultoria_apellido_materno' => $request->consultoria_apellido_materno,
                'fecha_ingreso_aduana' => $request->fecha_ingreso_aduana,
                'fecha_inicio_contrato' => $request->fecha_inicio_contrato,
                'fecha_fin_contrato' => $request->fecha_fin_contrato,
                'asignacion_familiar_desc' => $request->asignacion_familiar_desc,
                'asignacion_familiar_grado' => $request->asignacion_familiar_grado,
                'casos_especiales_desc' => $request->casos_especiales_desc,
                'casos_especiales_grado' => $request->casos_especiales_grado,
                'discapacidad_desc' => $request->discapacidad_desc,
                'discapacidad_grado' => $request->discapacidad_grado,
            ]);
        }

        $servidor->update($data);

        return redirect()->route('servidores.index')
            ->with('success', 'Actualizado correctamente');
    }

    public function destroy($id)
    {
        ServidorPublico::destroy($id);
        return redirect()->route('servidores.index')
            ->with('success', 'Eliminado correctamente');
    }
}

