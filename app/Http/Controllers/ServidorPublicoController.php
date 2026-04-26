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

                $servidores = ServidorPublico::orderBy('created_at', 'desc')
                    ->paginate(25);

            } else {

                $servidores = ServidorPublico::where('unidad', $area)
                    ->orWhere('sub_unidad', $area)
                    ->orderBy('created_at', 'desc')
                    ->paginate(25);
            }

        } else {

            $servidores = ServidorPublico::orderBy('created_at', 'desc')
                ->paginate(25);

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
            'tipo'             => 'required|in:item,consultoria',
            'unidad'           => 'required|string|max:150',
            'sub_unidad'       => 'required|string|max:150',
            'nombre'           => 'nullable|regex:/^[\pL\s]+$/u|max:100',
            'apellido_paterno' => 'nullable|regex:/^[\pL\s]+$/u|max:100',
            'apellido_materno' => 'nullable|regex:/^[\pL\s]+$/u|max:100',
            'fotografia'       => 'nullable|image|max:2048',
        ];

        if ($request->tipo === 'item') {
            $rules['numero_item']          = 'required|digits_between:1,10';
            $rules['cargo']                = 'required|string|max:150';
            $rules['fecha_ingreso_aduana'] = 'nullable|date';
            $rules['fecha_inicio_cargo']   = 'nullable|date';
        } else {
            $rules['contrato_numero']       = 'required|string|max:100';
            $rules['cargo_consultoria']     = 'required|string|max:150';
            $rules['nombre']                = 'nullable|regex:/^[\pL\s]+$/u|max:100';
            $rules['apellido_paterno']      = 'nullable|regex:/^[\pL\s]+$/u|max:100';
            $rules['fecha_ingreso_aduana']  = 'nullable|date';
            $rules['fecha_inicio_contrato'] = 'nullable|date';
            $rules['fecha_fin_contrato']    = 'nullable|date';
        }

        $request->validate($rules, [
            'unidad.required'              => 'La unidad es obligatoria.',
            'sub_unidad.required'          => 'La sub-unidad es obligatoria.',
            'numero_item.required'         => 'El N° de Ítem es obligatorio.',
            'numero_item.digits_between'   => 'El N° de Ítem debe contener solo números.',
            'cargo.required'               => 'El cargo es obligatorio.',
            'contrato_numero.required'     => 'El número de contrato es obligatorio.',
            'cargo_consultoria.required'   => 'El cargo de consultoría es obligatorio.',
            'nombre.regex'                 => 'El nombre solo debe contener letras.',
            'apellido_paterno.regex'       => 'El apellido paterno solo debe contener letras.',
            'apellido_materno.regex'       => 'El apellido materno solo debe contener letras.',
            'nombre.required'              => 'El nombre es obligatorio.',
            'apellido_paterno.required'    => 'El apellido paterno es obligatorio.',
        ]);

        $data = $request->except(['fotografia', 'designacion_tipos']);

        // Guardar designación como texto separado por comas
        $data['designacion'] = $request->designacion_tipos
            ? implode(', ', $request->designacion_tipos)
            : null;

        // Acefalía: si NO tiene todos los 5 mínimos, O si tiene los 5 pero sin datos extra
        $tieneNombre       = !empty($request->nombre);
        $tieneApellido     = !empty($request->apellido_paterno);
        $tieneFechas       = !empty($request->fecha_ingreso_aduana) || !empty($request->fecha_inicio_cargo) || !empty($request->fecha_inicio_contrato);
        $tieneInamovilidad = !empty($request->asignacion_familiar_desc) || !empty($request->casos_especiales_desc) || !empty($request->discapacidad_desc);
        $tieneFoto         = $request->hasFile('fotografia');
        $tieneCite         = !empty($request->cite_memorandum);

        $tieneDatosExtra = $tieneNombre || $tieneApellido || $tieneFechas || $tieneInamovilidad || $tieneFoto || $tieneCite;

        if ($request->tipo === 'item') {
            $tieneMinimos = !empty($request->numero_item) && !empty($request->cargo) && !empty($request->unidad) && !empty($request->sub_unidad) && !empty($data['designacion']);
        } else {
            $tieneMinimos = !empty($request->contrato_numero) && !empty($request->cargo_consultoria) && !empty($request->unidad) && !empty($request->sub_unidad) && !empty($data['designacion']);
        }

        // Es ÍTEM/CONSULTORÍA solo si tiene los 5 mínimos Y además datos extra
        $data['acefalia'] = !($tieneMinimos && $tieneDatosExtra);

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
            'unidad'           => 'required|string|max:150',
            'sub_unidad'       => 'required|string|max:150',
            'nombre'           => 'nullable|regex:/^[\pL\s]+$/u|max:100',
            'apellido_paterno' => 'nullable|regex:/^[\pL\s]+$/u|max:100',
            'apellido_materno' => 'nullable|regex:/^[\pL\s]+$/u|max:100',
            'fotografia'       => 'nullable|image|max:2048',
        ];

        if ($servidor->tipo === 'item') {
            $rules['numero_item']          = 'required|digits_between:1,10';
            $rules['cargo']                = 'required|string|max:150';
            $rules['fecha_ingreso_aduana'] = 'nullable|date';
            $rules['fecha_inicio_cargo']   = 'nullable|date';
        } else {
            $rules['contrato_numero']       = 'required|string|max:100';
            $rules['cargo_consultoria']     = 'required|string|max:150';
            $rules['nombre']                = 'nullable|regex:/^[\pL\s]+$/u|max:100';
            $rules['apellido_paterno']      = 'nullable|regex:/^[\pL\s]+$/u|max:100';
            $rules['fecha_ingreso_aduana']  = 'nullable|date';
            $rules['fecha_inicio_contrato'] = 'nullable|date';
            $rules['fecha_fin_contrato']    = 'nullable|date';
        }

        $request->validate($rules, [
            'unidad.required'              => 'La unidad es obligatoria.',
            'sub_unidad.required'          => 'La sub-unidad es obligatoria.',
            'numero_item.required'         => 'El N° de Ítem es obligatorio.',
            'numero_item.digits_between'   => 'El N° de Ítem debe contener solo números.',
            'cargo.required'               => 'El cargo es obligatorio.',
            'contrato_numero.required'     => 'El número de contrato es obligatorio.',
            'cargo_consultoria.required'   => 'El cargo de consultoría es obligatorio.',
            'nombre.regex'                 => 'El nombre solo debe contener letras.',
            'apellido_paterno.regex'       => 'El apellido paterno solo debe contener letras.',
            'apellido_materno.regex'       => 'El apellido materno solo debe contener letras.',
            'nombre.required'              => 'El nombre es obligatorio.',
            'apellido_paterno.required'    => 'El apellido paterno es obligatorio.',
        ]);

        $data = $request->except(['fotografia', '_token', '_method', 'designacion_tipos']);

        // Guardar designación como texto separado por comas
        $data['designacion'] = $request->designacion_tipos
            ? implode(', ', $request->designacion_tipos)
            : null;

        // Acefalía: si NO tiene todos los 5 mínimos, O si tiene los 5 pero sin datos extra
        $tieneNombre       = !empty($request->nombre);
        $tieneApellido     = !empty($request->apellido_paterno);
        $tieneFechas       = !empty($request->fecha_ingreso_aduana) || !empty($request->fecha_inicio_cargo) || !empty($request->fecha_inicio_contrato);
        $tieneInamovilidad = !empty($request->asignacion_familiar_desc) || !empty($request->casos_especiales_desc) || !empty($request->discapacidad_desc);
        $tieneFoto         = $request->hasFile('fotografia');
        $tieneCite         = !empty($request->cite_memorandum);

        $tieneDatosExtra = $tieneNombre || $tieneApellido || $tieneFechas || $tieneInamovilidad || $tieneFoto || $tieneCite;

        if ($servidor->tipo === 'item') {
            $tieneMinimos = !empty($request->numero_item) && !empty($request->cargo) && !empty($request->unidad) && !empty($request->sub_unidad) && !empty($data['designacion']);
        } else {
            $tieneMinimos = !empty($request->contrato_numero) && !empty($request->cargo_consultoria) && !empty($request->unidad) && !empty($request->sub_unidad) && !empty($data['designacion']);
        }

        // Es ÍTEM/CONSULTORÍA solo si tiene los 5 mínimos Y además datos extra
        $data['acefalia'] = !($tieneMinimos && $tieneDatosExtra);

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
