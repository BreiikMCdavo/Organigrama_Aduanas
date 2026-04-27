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
        // ── Detección de duplicados por número de ítem ──────────────────
        if ($request->tipo === 'item' && $request->filled('numero_item') && !$request->filled('accion_duplicado')) {
            $duplicados = ServidorPublico::where('numero_item', $request->numero_item)
                ->where('tipo', 'item')
                ->get()
                ->map(function ($s) {
                    $s->cargo_descripcion = $s->cargo ?? '(sin cargo)';
                    return $s;
                });

            if ($duplicados->count() > 0) {
                return back()
                    ->withInput()
                    ->with('duplicados', $duplicados);
            }
        }

        // Si eligió "reemplazar", marcar el registro anterior como acefalía
        if ($request->accion_duplicado === 'reemplazar' && $request->filled('numero_item')) {
            ServidorPublico::where('numero_item', $request->numero_item)
                ->where('tipo', 'item')
                ->update(['acefalia' => true, 'nombre' => null, 'apellido_paterno' => null, 'apellido_materno' => null]);
        }
        // Si eligió "adicionar" o "nuevo", simplemente continúa y guarda normalmente
        $rules = [
            'tipo'             => 'required|in:item,consultoria',
            'unidad'           => 'required|string|max:150',
            'sub_unidad'       => 'required|string|max:150',
            'nombre'           => 'nullable|regex:/^[\pL\s]+$/u|max:100',
            'apellido_paterno' => 'nullable|regex:/^[\pL\s]+$/u|max:100',
            'apellido_materno' => 'nullable|regex:/^[\pL\s]+$/u|max:100',
            'fotografia'       => 'nullable|image|max:2048',
            'accion_duplicado' => 'nullable|in:nuevo,reemplazar,adicionar',
            'cargo_a_reemplazar' => 'nullable|integer',
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

        $data = $request->except(['fotografia', 'accion_duplicado', 'cargo_a_reemplazar', 'designacion_tipos']);

        // Convertir checkboxes booleanos (HTML envía "on", BD necesita 0/1)
        $data['asignacion_familiar_check'] = $request->boolean('asignacion_familiar_check');
        $data['casos_especiales_check']    = $request->boolean('casos_especiales_check');
        $data['discapacidad_check']        = $request->boolean('discapacidad_check');

        // Guardar designación como texto separado por comas
        $data['designacion'] = $request->designacion_tipos
            ? implode(', ', $request->designacion_tipos)
            : null;

        // Detectar acefalía: sin nombre = acefalía
        $data['acefalia'] = empty(trim($request->nombre ?? ''));

        // Buscar duplicados solo si hay nombre completo
        if (!empty(trim($request->nombre ?? '')) && !empty(trim($request->apellido_paterno ?? ''))) {
            $duplicados = ServidorPublico::buscarPorNombreCompleto(
                $request->nombre,
                $request->apellido_paterno,
                $request->apellido_materno
            );

            if ($duplicados->count() > 0) {
                // Si hay duplicados pero no se especificó acción, volver con advertencia
                if (!$request->accion_duplicado) {
                    return redirect()->back()
                        ->withInput()
                        ->with('duplicados', $duplicados)
                        ->with('warning', 'Se encontraron registros existentes para esta persona. Por favor, seleccione una acción.');
                }

                // Validar que si se elige reemplazar, se seleccione un cargo
                if ($request->accion_duplicado === 'reemplazar' && !$request->cargo_a_reemplazar) {
                    return redirect()->back()
                        ->withInput()
                        ->with('duplicados', $duplicados)
                        ->with('error', 'Debe seleccionar un cargo existente para reemplazar.');
                }

                // Manejar acción seleccionada
                if ($request->accion_duplicado === 'reemplazar' && $request->cargo_a_reemplazar) {
                    $cargoExistente = $duplicados->where('id', $request->cargo_a_reemplazar)->first();
                    if ($cargoExistente) {
                        // Marcar cargo anterior como acefalía pero conservar información del cargo
                        $cargoExistente->update([
                            'acefalia' => true,
                            'nombre' => null,
                            'apellido_paterno' => null,
                            'apellido_materno' => null,
                        ]);

                        // Agregar mensaje informativo
                        session()->flash('info', "El cargo '{$cargoExistente->cargo_descripcion}' ha quedado vacante (acefalía).");
                    }

                // Si es 'nuevo', simplemente continuar sin mensajes especiales
            }
        }
    }

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

        // Convertir checkboxes booleanos
        $data['asignacion_familiar_check'] = $request->boolean('asignacion_familiar_check');
        $data['casos_especiales_check']    = $request->boolean('casos_especiales_check');
        $data['discapacidad_check']        = $request->boolean('discapacidad_check');

        // Guardar designación como texto separado por comas
        $data['designacion'] = $request->designacion_tipos
            ? implode(', ', $request->designacion_tipos)
            : null;

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

    /**
     * Generar reporte de servidores con items
     */
    public function reporteItems()
    {
        $servidores = ServidorPublico::where('tipo', 'item')
            ->where(function($query) {
                $query->whereNull('acefalia')->orWhere('acefalia', false);
            })
            ->orderBy('unidad')
            ->orderBy('sub_unidad')
            ->get();

        $headers = [
            'TIPO', 'NOMBRE', 'APELLIDO PATERNO', 'APELLIDO MATERNO', 'UNIDAD', 'SUB-UNIDAD',
            'N° ITEM', 'CITE MEMORANDUM', 'CARGO', 'FECHA INICIO CARGO', 'FECHA FIN CARGO',
            'COD. FUNCIONARIO', 'ESCALA SALARIAL', 'ASIGNACION FAMILIAR', 'GRADO AF',
            'CASOS ESPECIALES', 'GRADO CE', 'DISCAPACIDAD', 'GRADO DISC', 
            'TIPO DISC', 'CARNET DISC', 'VENCE DISC', 'FOTO'
        ];

        $csvData = [];
        $csvData[] = implode(',', $headers);

        foreach ($servidores as $servidor) {
            $row = [
                $servidor->tipo ?? '',
                $this->escapeCsv($servidor->nombre ?? ''),
                $this->escapeCsv($servidor->apellido_paterno ?? ''),
                $this->escapeCsv($servidor->apellido_materno ?? ''),
                $this->escapeCsv($servidor->unidad ?? ''),
                $this->escapeCsv($servidor->sub_unidad ?? ''),
                $servidor->numero_item ?? '',
                $this->escapeCsv($servidor->cite_memorandum ?? ''),
                $this->escapeCsv($servidor->cargo ?? ''),
                $servidor->fecha_inicio_cargo ?? '',
                $servidor->fecha_fin_cargo ?? '',
                $servidor->cod_funcionario ?? '',
                $servidor->escala_salarial ?? '',
                $this->escapeCsv($servidor->asignacion_familiar_desc ?? ''),
                $servidor->asignacion_familiar_grado ?? '',
                $this->escapeCsv($servidor->casos_especiales_desc ?? ''),
                $servidor->casos_especiales_grado ?? '',
                $this->escapeCsv($servidor->discapacidad_desc ?? ''),
                $servidor->discapacidad_grado ?? '',
                $this->escapeCsv($servidor->discapacidad_tipo ?? ''),
                $servidor->discapacidad_carnet ?? '',
                $servidor->discapacidad_vence ?? '',
                $servidor->fotografia ?? ''
            ];
            $csvData[] = implode(',', $row);
        }

        $filename = 'reporte_items_' . date('Y-m-d_H-i-s') . '.xls';
        
        // Generar contenido HTML que Excel puede abrir
        $htmlContent = $this->generateExcelHtml($csvData, 'Reporte de Items - Servidores Públicos');
        
        return response($htmlContent)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
}

/**
 * Generar reporte de servidores con items en PDF
 */
public function reporteItemsPdf()
{
    $servidores = ServidorPublico::where('tipo', 'item')
        ->where(function($query) {
            $query->whereNull('acefalia')->orWhere('acefalia', false);
        })
        ->orderBy('unidad')
        ->orderBy('sub_unidad')
        ->get();

    $headers = [
        'TIPO', 'NOMBRE', 'APELLIDO PATERNO', 'APELLIDO MATERNO', 'UNIDAD', 'SUB-UNIDAD',
        'N° ITEM', 'CITE MEMORANDUM', 'CARGO', 'FECHA INICIO CARGO', 'FECHA FIN CARGO',
        'COD. FUNCIONARIO', 'ESCALA SALARIAL', 'ASIGNACION FAMILIAR', 'GRADO AF',
        'CASOS ESPECIALES', 'GRADO CE', 'DISCAPACIDAD', 'GRADO DISC', 
        'TIPO DISC', 'CARNET DISC', 'VENCE DISC'
    ];

    $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Items - Servidores Públicos</title>
    <style>
        @page {
            margin: 20mm;
            @bottom-center {
                content: "Página " counter(page) " de " counter(pages);
                font-size: 10px;
                color: #666;
            }
        }
        
        body { 
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 20px;
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 50%, #bcccdc 100%);
            min-height: 100vh;
        }
        
        .header {
            background: linear-gradient(135deg, #0a1628 0%, #1a3a6b 50%, #2c5282 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(10, 22, 40, 0.4);
            position: relative;
            overflow: hidden;
            border: 2px solid #1565c0;
        }
        
        .header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(10, 22, 40, 0.95) 0%, rgba(26, 58, 107, 0.95) 50%, rgba(44, 82, 130, 0.95) 100%);
            z-index: 0;
        }
        
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        
        .header .subtitle {
            text-align: center;
            margin-top: 10px;
            opacity: 0.9;
            font-size: 14px;
            position: relative;
            z-index: 1;
        }
        
        .info-cards {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-card {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-left: 4px solid #1565c0;
            border-top: 1px solid #1565c0;
        }
        
        .info-card h3 {
            margin: 0 0 10px 0;
            color: #0a1628;
            font-size: 14px;
            font-weight: 700;
        }
        
        .info-card .value {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
        }
        
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 0;
        }
        
        th { 
            background: linear-gradient(135deg, #0a1628 0%, #1a3a6b 100%); 
            color: white; 
            padding: 12px 8px; 
            text-align: left; 
            font-weight: 700; 
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-right: 1px solid rgba(255,255,255,0.2);
        }
        
        th:last-child {
            border-right: none;
        }
        
        td { 
            border-bottom: 1px solid #e8e8e8; 
            padding: 10px 8px; 
            font-size: 10px;
            color: #333;
        }
        
        tr:nth-child(even) { background-color: #f8f9fa; }
        tr:hover { background-color: #e3f2fd; }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 11px;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .footer .total {
            font-size: 16px;
            font-weight: 700;
            color: #0a1628;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(10, 22, 40, 0.03);
            font-weight: 900;
            z-index: -1;
            pointer-events: none;
            text-transform: uppercase;
            letter-spacing: 5px;
        }
    </style>
</head>
<body>
    <div class="watermark">ADUANA</div>
    
    <div class="header">
        <h1>📊 REPORTE DE SERVIDORES PÚBLICOS CON ITEMS</h1>
        <div class="subtitle">
            Gerencia Regional La Paz - Aduana Nacional de Bolivia<br>
            Sistema de Gestión de Servidores Públicos
        </div>
    </div>
    
    <div class="info-cards">
        <div class="info-card">
            <h3>📋 TOTAL DE SERVIDORES</h3>
            <div class="value">' . $servidores->count() . '</div>
        </div>
        <div class="info-card">
            <h3>📅 FECHA DE GENERACIÓN</h3>
            <div class="value" style="font-size: 14px;">' . date('d/m/Y H:i:s') . '</div>
        </div>
        <div class="info-card">
            <h3>🏢 TIPO DE REPORTE</h3>
            <div class="value" style="font-size: 14px;">ITEMS</div>
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>';

        foreach ($headers as $header) {
            $html .= '<th>' . htmlspecialchars($header) . '</th>';
        }

        $html .= '</tr>
            </thead>
            <tbody>';

        $rowNumber = 1;
        foreach ($servidores as $servidor) {
            $rowClass = $rowNumber % 2 === 0 ? 'even' : 'odd';
            $html .= '<tr class="' . $rowClass . '">
                <td>' . htmlspecialchars($servidor->tipo ?? '') . '</td>
                <td><strong>' . htmlspecialchars($servidor->nombre ?? '') . '</strong></td>
                <td><strong>' . htmlspecialchars($servidor->apellido_paterno ?? '') . '</strong></td>
                <td>' . htmlspecialchars($servidor->apellido_materno ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->unidad ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->sub_unidad ?? '') . '</td>
                <td><strong>' . htmlspecialchars($servidor->numero_item ?? '') . '</strong></td>
                <td>' . htmlspecialchars($servidor->cite_memorandum ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->cargo ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->fecha_inicio_cargo ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->fecha_fin_cargo ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->cod_funcionario ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->escala_salarial ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->asignacion_familiar_desc ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->asignacion_familiar_grado ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->casos_especiales_desc ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->casos_especiales_grado ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->discapacidad_desc ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->discapacidad_grado ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->discapacidad_tipo ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->discapacidad_carnet ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->discapacidad_vence ?? '') . '</td>
            </tr>';
            $rowNumber++;
        }

        $html .= '</tbody>
        </table>
    </div>
    
    <div class="footer">
        <div class="total">
            📊 TOTAL DE SERVIDORES CON ITEMS: ' . $servidores->count() . '
        </div>
        <div>
            <strong>Reporte generado automáticamente por el Sistema de Gestión de Servidores Públicos</strong><br>
            Gerencia Regional La Paz - Aduana Nacional de Bolivia<br>
            © ' . date('Y') . ' Todos los derechos reservados
        </div>
    </div>
</body>
</html>';

        $filename = 'reporte_items_' . date('Y-m-d_H-i-s') . '.pdf';
        
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    /**
     * Generar reporte de servidores de consultoría en Excel
     */
    public function reporteConsultoria()
    {
        $servidores = ServidorPublico::where('tipo', 'consultoria')
            ->where(function($query) {
                $query->whereNull('acefalia')->orWhere('acefalia', false);
            })
            ->orderBy('unidad')
            ->orderBy('sub_unidad')
            ->get();

        $headers = [
            'TIPO', 'NOMBRE', 'APELLIDO PATERNO', 'APELLIDO MATERNO', 'UNIDAD', 'SUB-UNIDAD',
            'CONTRATO', 'COD. FUNCIONARIO', 'ESCALA SALARIAL', 'CARGO', 
            'FECHA INICIO CARGO', 'FECHA FIN CARGO', 'DESIGNACION', 'FECHA INICIO DESIGNACION',
            'FECHA FIN DESIGNACION', 'ASIGNACION FAMILIAR', 'GRADO AF',
            'CASOS ESPECIALES', 'GRADO CE', 'DISCAPACIDAD', 'GRADO DISC', 
            'TIPO DISC', 'CARNET DISC', 'VENCE DISC'
        ];

        $csvData = [];
        $csvData[] = implode(',', $headers);

        foreach ($servidores as $servidor) {
            $row = [
                $servidor->tipo ?? '',
                $this->escapeCsv($servidor->nombre ?? ''),
                $this->escapeCsv($servidor->apellido_paterno ?? ''),
                $this->escapeCsv($servidor->apellido_materno ?? ''),
                $this->escapeCsv($servidor->unidad ?? ''),
                $this->escapeCsv($servidor->sub_unidad ?? ''),
                $servidor->contrato ?? '',
                $servidor->cod_funcionario ?? '',
                $servidor->escala_salarial ?? '',
                $this->escapeCsv($servidor->cargo ?? ''),
                $servidor->fecha_inicio_cargo ?? '',
                $servidor->fecha_fin_cargo ?? '',
                $this->escapeCsv($servidor->designacion ?? ''),
                $servidor->fecha_inicio_designacion ?? '',
                $servidor->fecha_fin_designacion ?? '',
                $this->escapeCsv($servidor->asignacion_familiar_desc ?? ''),
                $servidor->asignacion_familiar_grado ?? '',
                $this->escapeCsv($servidor->casos_especiales_desc ?? ''),
                $servidor->casos_especiales_grado ?? '',
                $this->escapeCsv($servidor->discapacidad_desc ?? ''),
                $servidor->discapacidad_grado ?? '',
                $this->escapeCsv($servidor->discapacidad_tipo ?? ''),
                $servidor->discapacidad_carnet ?? '',
                $servidor->discapacidad_vence ?? ''
            ];
            $csvData[] = implode(',', $row);
        }

        $filename = 'reporte_consultoria_' . date('Y-m-d_H-i-s') . '.xls';
        
        // Generar contenido HTML que Excel puede abrir
        $htmlContent = $this->generateExcelHtml($csvData, 'Reporte de Consultoría - Servidores Públicos');
        
        return response($htmlContent)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    /**
     * Generar reporte de servidores de consultoría en PDF
     */
    public function reporteConsultoriaPdf()
    {
        $servidores = ServidorPublico::where('tipo', 'consultoria')
            ->where(function($query) {
                $query->whereNull('acefalia')->orWhere('acefalia', false);
            })
            ->orderBy('unidad')
            ->orderBy('sub_unidad')
            ->get();

        $headers = [
            'TIPO', 'NOMBRE', 'APELLIDO PATERNO', 'APELLIDO MATERNO', 'UNIDAD', 'SUB-UNIDAD',
            'CONTRATO', 'COD. FUNCIONARIO', 'ESCALA SALARIAL', 'CARGO', 
            'FECHA INICIO CARGO', 'FECHA FIN CARGO', 'DESIGNACION', 'FECHA INICIO DESIGNACION',
            'FECHA FIN DESIGNACION', 'ASIGNACION FAMILIAR', 'GRADO AF',
            'CASOS ESPECIALES', 'GRADO CE', 'DISCAPACIDAD', 'GRADO DISC', 
            'TIPO DISC', 'CARNET DISC', 'VENCE DISC'
        ];

        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Consultoría - Servidores Públicos</title>
    <style>
        @page {
            margin: 20mm;
            @bottom-center {
                content: "Página " counter(page) " de " counter(pages);
                font-size: 10px;
                color: #666;
            }
        }
        
        body { 
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 20px;
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 50%, #bcccdc 100%);
            min-height: 100vh;
        }
        
        .header {
            background: linear-gradient(135deg, #0a1628 0%, #1a3a6b 50%, #2c5282 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(10, 22, 40, 0.4);
            position: relative;
            overflow: hidden;
            border: 2px solid #1565c0;
        }
        
        .header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(10, 22, 40, 0.95) 0%, rgba(26, 58, 107, 0.95) 50%, rgba(44, 82, 130, 0.95) 100%);
            z-index: 0;
        }
        
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        
        .header .subtitle {
            text-align: center;
            margin-top: 10px;
            opacity: 0.9;
            font-size: 14px;
            position: relative;
            z-index: 1;
        }
        
        .info-cards {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-card {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-left: 4px solid #1565c0;
            border-top: 1px solid #1565c0;
        }
        
        .info-card h3 {
            margin: 0 0 10px 0;
            color: #0a1628;
            font-size: 14px;
            font-weight: 700;
        }
        
        .info-card .value {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
        }
        
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 0;
        }
        
        th { 
            background: linear-gradient(135deg, #0a1628 0%, #1a3a6b 100%); 
            color: white; 
            padding: 12px 8px; 
            text-align: left; 
            font-weight: 700; 
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-right: 1px solid rgba(255,255,255,0.2);
        }
        
        th:last-child {
            border-right: none;
        }
        
        td { 
            border-bottom: 1px solid #e8e8e8; 
            padding: 10px 8px; 
            font-size: 10px;
            color: #333;
        }
        
        tr:nth-child(even) { background-color: #f8f9fa; }
        tr:hover { background-color: #e8f5e8; }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 11px;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .footer .total {
            font-size: 16px;
            font-weight: 700;
            color: #0a1628;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(10, 22, 40, 0.03);
            font-weight: 900;
            z-index: -1;
            pointer-events: none;
            text-transform: uppercase;
            letter-spacing: 5px;
        }
    </style>
</head>
<body>
    <div class="watermark">ADUANA</div>
    
    <div class="header">
        <h1>📋 REPORTE DE SERVIDORES DE CONSULTORÍA</h1>
        <div class="subtitle">
            Gerencia Regional La Paz - Aduana Nacional de Bolivia<br>
            Sistema de Gestión de Servidores Públicos
        </div>
    </div>
    
    <div class="info-cards">
        <div class="info-card">
            <h3>📋 TOTAL DE SERVIDORES</h3>
            <div class="value">' . $servidores->count() . '</div>
        </div>
        <div class="info-card">
            <h3>📅 FECHA DE GENERACIÓN</h3>
            <div class="value" style="font-size: 14px;">' . date('d/m/Y H:i:s') . '</div>
        </div>
        <div class="info-card">
            <h3>🏢 TIPO DE REPORTE</h3>
            <div class="value" style="font-size: 14px;">CONSULTORÍA</div>
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>';

        foreach ($headers as $header) {
            $html .= '<th>' . htmlspecialchars($header) . '</th>';
        }

        $html .= '</tr>
            </thead>
            <tbody>';

        $rowNumber = 1;
        foreach ($servidores as $servidor) {
            $rowClass = $rowNumber % 2 === 0 ? 'even' : 'odd';
            $html .= '<tr class="' . $rowClass . '">
                <td>' . htmlspecialchars($servidor->tipo ?? '') . '</td>
                <td><strong>' . htmlspecialchars($servidor->nombre ?? '') . '</strong></td>
                <td><strong>' . htmlspecialchars($servidor->apellido_paterno ?? '') . '</strong></td>
                <td>' . htmlspecialchars($servidor->apellido_materno ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->unidad ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->sub_unidad ?? '') . '</td>
                <td><strong>' . htmlspecialchars($servidor->contrato_numero ?? '') . '</strong></td>
                <td>' . htmlspecialchars($servidor->cod_funcionario ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->escala_salarial ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->cargo ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->fecha_inicio_cargo ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->fecha_fin_cargo ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->designacion ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->designacion_inicio ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->designacion_fin ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->asignacion_familiar_desc ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->asignacion_familiar_grado ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->casos_especiales_desc ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->casos_especiales_grado ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->discapacidad_desc ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->discapacidad_grado ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->discapacidad_tipo ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->discapacidad_carnet ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->discapacidad_vence ?? '') . '</td>
            </tr>';
            $rowNumber++;
        }

        $html .= '</tbody>
        </table>
    </div>
    
    <div class="footer">
        <div class="total">
            📋 TOTAL DE SERVIDORES DE CONSULTORÍA: ' . $servidores->count() . '
        </div>
        <div>
            <strong>Reporte generado automáticamente por el Sistema de Gestión de Servidores Públicos</strong><br>
            Gerencia Regional La Paz - Aduana Nacional de Bolivia<br>
            © ' . date('Y') . ' Todos los derechos reservados
        </div>
    </div>
</body>
</html>';

        $filename = 'reporte_consultoria_' . date('Y-m-d_H-i-s') . '.pdf';
        
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    /**
     * Generar reporte de acefalías en Excel
     */
    public function reporteAcefalias()
    {
        $servidores = ServidorPublico::where('acefalia', true)
            ->orderBy('unidad')
            ->orderBy('sub_unidad')
            ->get();

        $headers = [
            'TIPO', 'NOMBRE', 'APELLIDO PATERNO', 'APELLIDO MATERNO', 'UNIDAD', 'SUB-UNIDAD',
            'N° ITEM', 'CITE MEMORANDUM', 'CARGO', 'FECHA INICIO CARGO', 'FECHA FIN CARGO',
            'COD. FUNCIONARIO', 'ESCALA SALARIAL', 'CONTRATO', 'DESIGNACION', 
            'FECHA INICIO DESIGNACION', 'FECHA FIN DESIGNACION'
        ];

        $csvData = [];
        $csvData[] = implode(',', $headers);

        foreach ($servidores as $servidor) {
            $row = [
                $servidor->tipo ?? '',
                $this->escapeCsv($servidor->nombre ?? ''),
                $this->escapeCsv($servidor->apellido_paterno ?? ''),
                $this->escapeCsv($servidor->apellido_materno ?? ''),
                $this->escapeCsv($servidor->unidad ?? ''),
                $this->escapeCsv($servidor->sub_unidad ?? ''),
                $servidor->numero_item ?? '',
                $this->escapeCsv($servidor->cite_memorandum ?? ''),
                $this->escapeCsv($servidor->cargo ?? ''),
                $servidor->fecha_inicio_cargo ?? '',
                $servidor->fecha_fin_cargo ?? '',
                $servidor->cod_funcionario ?? '',
                $servidor->escala_salarial ?? '',
                $this->escapeCsv($servidor->contrato_numero ?? ''),
                $this->escapeCsv($servidor->designacion ?? ''),
                $servidor->designacion_inicio ?? '',
                $servidor->designacion_fin ?? ''
            ];
            $csvData[] = implode(',', $row);
        }

        $filename = 'reporte_acefalias_' . date('Y-m-d_H-i-s') . '.xls';
        
        // Generar contenido HTML que Excel puede abrir
        $htmlContent = $this->generateExcelHtml($csvData, 'Reporte de Acefalías - Plazas Vacantes');
        
        return response($htmlContent)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    /**
     * Generar reporte de acefalías en PDF
     */
    public function reporteAcefaliasPdf()
    {
        $servidores = ServidorPublico::where('acefalia', true)
            ->orderBy('unidad')
            ->orderBy('sub_unidad')
            ->get();

        $headers = [
            'TIPO', 'NOMBRE', 'APELLIDO PATERNO', 'APELLIDO MATERNO', 'UNIDAD', 'SUB-UNIDAD',
            'N° ITEM', 'CITE MEMORANDUM', 'CARGO', 'FECHA INICIO CARGO', 'FECHA FIN CARGO',
            'COD. FUNCIONARIO', 'ESCALA SALARIAL', 'CONTRATO', 'DESIGNACION', 
            'FECHA INICIO DESIGNACION', 'FECHA FIN DESIGNACION'
        ];

        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Acefalías - Plazas Vacantes</title>
    <style>
        @page {
            margin: 20mm;
            @bottom-center {
                content: "Página " counter(page) " de " counter(pages);
                font-size: 10px;
                color: #666;
            }
        }
        
        body { 
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 20px;
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 50%, #bcccdc 100%);
            min-height: 100vh;
        }
        
        .header {
            background: linear-gradient(135deg, #0a1628 0%, #1a3a6b 50%, #2c5282 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(10, 22, 40, 0.4);
            position: relative;
            overflow: hidden;
            border: 2px solid #1565c0;
        }
        
        .header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(10, 22, 40, 0.95) 0%, rgba(26, 58, 107, 0.95) 50%, rgba(44, 82, 130, 0.95) 100%);
            z-index: 0;
        }
        
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        
        .header .subtitle {
            text-align: center;
            margin-top: 10px;
            opacity: 0.9;
            font-size: 14px;
            position: relative;
            z-index: 1;
        }
        
        .alert-banner {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(231, 76, 60, 0.2);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .alert-banner .icon {
            font-size: 40px;
            opacity: 0.8;
        }
        
        .alert-banner .content h3 {
            margin: 0 0 5px 0;
            font-size: 18px;
            font-weight: 700;
        }
        
        .alert-banner .content p {
            margin: 0;
            font-size: 14px;
            opacity: 0.9;
        }
        
        .info-cards {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-card {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-left: 4px solid #1565c0;
            border-top: 1px solid #1565c0;
        }
        
        .info-card h3 {
            margin: 0 0 10px 0;
            color: #0a1628;
            font-size: 14px;
            font-weight: 700;
        }
        
        .info-card .value {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
        }
        
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 0;
        }
        
        th { 
            background: linear-gradient(135deg, #0a1628 0%, #1a3a6b 100%); 
            color: white; 
            padding: 12px 8px; 
            text-align: left; 
            font-weight: 700; 
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-right: 1px solid rgba(255,255,255,0.2);
        }
        
        th:last-child {
            border-right: none;
        }
        
        td { 
            border-bottom: 1px solid #e8e8e8; 
            padding: 10px 8px; 
            font-size: 10px;
            color: #333;
        }
        
        tr:nth-child(even) { background-color: #f8f9fa; }
        tr:hover { background-color: #ffe8e8; }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 11px;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .footer .total {
            font-size: 16px;
            font-weight: 700;
            color: #0a1628;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(10, 22, 40, 0.03);
            font-weight: 900;
            z-index: -1;
            pointer-events: none;
            text-transform: uppercase;
            letter-spacing: 5px;
        }
    </style>
</head>
<body>
    <div class="watermark">ADUANA</div>
    
    <div class="header">
        <h1>⚠️ REPORTE DE PLAZAS VACANTES (ACEFALÍAS)</h1>
        <div class="subtitle">
            Gerencia Regional La Paz - Aduana Nacional de Bolivia<br>
            Sistema de Gestión de Servidores Públicos
        </div>
    </div>
    
    <div class="alert-banner">
        <div class="icon">⚠️</div>
        <div class="content">
            <h3>ATENCIÓN: PLAZAS VACANTES</h3>
            <p>Este reporte muestra todas las plazas vacantes (acefalías) registradas en el sistema. Requiere acción inmediata para cobertura.</p>
        </div>
    </div>
    
    <div class="info-cards">
        <div class="info-card">
            <h3>⚠️ TOTAL DE ACEFALÍAS</h3>
            <div class="value">' . $servidores->count() . '</div>
        </div>
        <div class="info-card">
            <h3>📅 FECHA DE GENERACIÓN</h3>
            <div class="value" style="font-size: 14px;">' . date('d/m/Y H:i:s') . '</div>
        </div>
        <div class="info-card">
            <h3>🏢 ESTADO CRÍTICO</h3>
            <div class="value" style="font-size: 14px;">PLAZAS VACANTES</div>
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>';

        foreach ($headers as $header) {
            $html .= '<th>' . htmlspecialchars($header) . '</th>';
        }

        $html .= '</tr>
            </thead>
            <tbody>';

        $rowNumber = 1;
        foreach ($servidores as $servidor) {
            $rowClass = $rowNumber % 2 === 0 ? 'even' : 'odd';
            $html .= '<tr class="' . $rowClass . '">
                <td>' . htmlspecialchars($servidor->tipo ?? '') . '</td>
                <td><strong>' . htmlspecialchars($servidor->nombre ?? '') . '</strong></td>
                <td><strong>' . htmlspecialchars($servidor->apellido_paterno ?? '') . '</strong></td>
                <td>' . htmlspecialchars($servidor->apellido_materno ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->unidad ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->sub_unidad ?? '') . '</td>
                <td><strong>' . htmlspecialchars($servidor->numero_item ?? '') . '</strong></td>
                <td>' . htmlspecialchars($servidor->cite_memorandum ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->cargo ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->fecha_inicio_cargo ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->fecha_fin_cargo ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->cod_funcionario ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->escala_salarial ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->contrato_numero ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->designacion ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->designacion_inicio ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->designacion_fin ?? '') . '</td>
            </tr>';
            $rowNumber++;
        }

        $html .= '</tbody>
        </table>
    </div>
    
    <div class="footer">
        <div class="total">
            ⚠️ TOTAL DE PLAZAS VACANTES (ACEFALÍAS): ' . $servidores->count() . '
        </div>
        <div>
            <strong>Reporte generado automáticamente por el Sistema de Gestión de Servidores Públicos</strong><br>
            Gerencia Regional La Paz - Aduana Nacional de Bolivia<br>
            © ' . date('Y') . ' Todos los derechos reservados
        </div>
    </div>
</body>
</html>';

        $filename = 'reporte_acefalias_' . date('Y-m-d_H-i-s') . '.pdf';
        
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    /**
     * Generar contenido HTML que Excel puede abrir
     */
    private function generateExcelHtml($csvData, $title)
    {
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>' . htmlspecialchars($title) . '</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #ffffff;
        }
        .header {
            background: linear-gradient(135deg, #0a1628 0%, #1a3a6b 50%, #2c5282 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #1a3a6b;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>' . htmlspecialchars($title) . '</h1>
        <p>Generado el ' . date('d/m/Y H:i:s') . '</p>
    </div>
    
    <table>';

        // Agregar filas de datos
        foreach ($csvData as $row) {
            $cells = explode(',', $row);
            $html .= '<tr>';
            foreach ($cells as $cell) {
                $html .= '<td>' . htmlspecialchars($cell) . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</table>
    
    <div class="footer">
        <strong>Reporte generado automáticamente por el Sistema de Gestión de Servidores Públicos</strong><br>
        Gerencia Regional La Paz - Aduana Nacional de Bolivia<br>
        © ' . date('Y') . ' Todos los derechos reservados
    </div>
</body>
</html>';

        return $html;
    }

    /**
     * Escapar caracteres especiales para CSV
     */
    private function escapeCsv($value)
    {
        if (str_contains($value, ',') || str_contains($value, '"') || str_contains($value, "\n")) {
            $value = str_replace('"', '""', $value);
            return '"' . $value . '"';
        }
        return $value;
    }

    /**
     * Generar contenido CSV (mantener como respaldo)
     */
    /**
     * Generar reporte individual por unidad
     */
    public function reportePorUnidad($nombre)
    {
        // Decodificar el nombre de la unidad (URL encoded)
        $nombreUnidad = urldecode($nombre);
        
        // Obtener todos los servidores de la unidad específica
        $servidores = ServidorPublico::where('unidad', $nombreUnidad)
            ->orderBy('sub_unidad')
            ->orderBy('tipo')
            ->get();

        // Headers para el reporte
        $headers = [
            'TIPO', 'NOMBRE', 'APELLIDO PATERNO', 'APELLIDO MATERNO', 'UNIDAD', 'SUB-UNIDAD',
            'N° ITEM', 'CITE MEMORANDUM', 'CARGO', 'FECHA INICIO CARGO', 'FECHA FIN CARGO',
            'COD. FUNCIONARIO', 'ESCALA SALARIAL', 'ASIGNACION FAMILIAR', 'GRADO AF',
            'CASOS ESPECIALES', 'GRADO CE', 'DISCAPACIDAD', 'GRADO DISC', 
            'TIPO DISC', 'CARNET DISC', 'VENCE DISC'
        ];

        // Generar contenido CSV
        $csvData = [];
        $csvData[] = implode(',', $headers);

        foreach ($servidores as $servidor) {
            $row = [
                $servidor->tipo ?? '',
                $this->escapeCsv($servidor->nombre ?? ''),
                $this->escapeCsv($servidor->apellido_paterno ?? ''),
                $this->escapeCsv($servidor->apellido_materno ?? ''),
                $this->escapeCsv($servidor->unidad ?? ''),
                $this->escapeCsv($servidor->sub_unidad ?? ''),
                $servidor->numero_item ?? '',
                $this->escapeCsv($servidor->cite_memorandum ?? ''),
                $this->escapeCsv($servidor->cargo ?? ''),
                $servidor->fecha_inicio_cargo ?? '',
                $servidor->fecha_fin_cargo ?? '',
                $servidor->cod_funcionario ?? '',
                $servidor->escala_salarial ?? '',
                $this->escapeCsv($servidor->asignacion_familiar_desc ?? ''),
                $servidor->asignacion_familiar_grado ?? '',
                $this->escapeCsv($servidor->casos_especiales_desc ?? ''),
                $servidor->casos_especiales_grado ?? '',
                $this->escapeCsv($servidor->discapacidad_desc ?? ''),
                $servidor->discapacidad_grado ?? '',
                $this->escapeCsv($servidor->discapacidad_tipo ?? ''),
                $servidor->discapacidad_carnet ?? '',
                $servidor->discapacidad_vence ?? ''
            ];
            $csvData[] = implode(',', $row);
        }

        // Generar nombre de archivo
        $nombreArchivo = 'reporte_' . str_replace(' ', '_', strtolower($nombreUnidad)) . '_' . date('Y-m-d_H-i-s') . '.xls';
        
        // Generar contenido HTML que Excel puede abrir
        $htmlContent = $this->generateExcelHtml($csvData, 'Reporte de ' . $nombreUnidad . ' - Servidores Públicos');
        
        return response($htmlContent)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="' . $nombreArchivo . '"');
    }

    private function generateCsv($data)
    {
        $csv = '';
        foreach ($data as $row) {
            $csv .= $row . "\n";
        }
        return $csv;
    }
}
