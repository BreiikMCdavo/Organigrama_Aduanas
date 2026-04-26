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

        $data = $request->except('fotografia');

        // Detectar acefalía: sin nombre = acefalía
        $data['acefalia'] = empty(trim($request->nombre ?? ''));

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

        $data = $request->except(['fotografia', '_token', '_method']);

        // Recalcular acefalía al editar
        $data['acefalia'] = empty(trim($request->nombre ?? ''));

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
