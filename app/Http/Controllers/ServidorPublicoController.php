<?php

namespace App\Http\Controllers;

use App\Models\ServidorPublico;
use App\Services\EstructuraOrganizacionalService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;

class ServidorPublicoController extends Controller
{
    private const EXPORT_CHUNK_SIZE = 500;
    private const PDF_PART_SIZE = 500;

    public function index(Request $request)
    {
        $areaActual = trim((string) $request->query('area', ''));
        $areaActual = $areaActual !== '' ? $areaActual : null;
        $buscar = trim((string) $request->query('buscar', ''));
        $buscar = $buscar !== '' ? $buscar : null;

        $servidores = ServidorPublico::query()
            ->forArea($areaActual)
            ->search($buscar)
            ->orderBy('created_at', 'desc')
            ->paginate(25)
            ->appends($request->query());

        $estadisticas = ServidorPublico::statsForArea($areaActual);
        $resumenUnidades = ServidorPublico::breakdownForArea($areaActual);

        return view('servidores.index', compact('servidores', 'areaActual', 'buscar', 'estadisticas', 'resumenUnidades'));
    }

    public function sugerencias(Request $request)
    {
        $areaActual = trim((string) $request->query('area', ''));
        $areaActual = $areaActual !== '' ? $areaActual : null;
        $buscar = trim((string) $request->query('buscar', ''));

        if ($buscar === '') {
            return response()->json([]);
        }

        $servidores = ServidorPublico::query()
            ->forArea($areaActual)
            ->search($buscar)
            ->orderBy('nombre')
            ->orderBy('apellido_paterno')
            ->limit(8)
            ->get();

        return response()->json(
            $servidores->map(function (ServidorPublico $servidor) {
                $codigo = $servidor->tipo === 'item'
                    ? 'Item ' . ($servidor->numero_item ?? 's/n')
                    : 'Contrato ' . ($servidor->contrato_numero ?? 's/n');

                return [
                    'nombre' => $servidor->nombre_completo ?: 'Acefalía',
                    'codigo' => $codigo,
                    'cargo' => $servidor->cargo_descripcion,
                    'unidad' => trim(($servidor->unidad ?? '') . ' / ' . ($servidor->sub_unidad ?? ''), ' /'),
                    'url' => route('servidores.show', $servidor),
                ];
            })->values()
        );
    }
    public function create()
    {
        return view('servidores.create');
    }

    public function store(Request $request)
    {
        $cargoMarcadoAcefalia = false;

        // ── Detección de duplicados por número de ítem ──────────────────
        // Los items duplicados se normalizan automaticamente despues de guardar.

        // Si eligió "reemplazar", marcar el registro anterior como acefalía
        if ($request->accion_duplicado === 'reemplazar' && $request->filled('cargo_a_reemplazar')) {
            $cargoReemplazado = ServidorPublico::find($request->cargo_a_reemplazar);

            if ($cargoReemplazado) {
                $cargoReemplazado->update([
                    'acefalia' => true,
                    'nombre' => null,
                    'apellido_paterno' => null,
                    'apellido_materno' => null,
                ]);

                $this->sincronizarEstructura($cargoReemplazado->fresh());
                $cargoMarcadoAcefalia = true;
            }
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
            'accion_duplicado' => 'nullable|in:nuevo,reemplazar,adicionar,comision',
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
        $designacionTipos = $request->input('designacion_tipos', []);
        $designacionTipos = is_array($designacionTipos) ? $designacionTipos : [];

        if ($request->accion_duplicado === 'comision' && !in_array('Comisión', $designacionTipos, true)) {
            $designacionTipos[] = 'Comisión';
        }

        $data['designacion'] = $designacionTipos
            ? implode(', ', array_unique($designacionTipos))
            : null;

        // Detectar acefalía: sin nombre = acefalía
        $data['acefalia'] = empty(trim($request->nombre ?? ''));

        // Buscar duplicados solo si hay nombre completo
        if ($request->tipo !== 'item' && !empty(trim($request->nombre ?? '')) && !empty(trim($request->apellido_paterno ?? ''))) {
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

                $duplicadoItemActivo = $duplicados->first(function ($duplicado) {
                    return $duplicado->tipo === 'item' && ! $duplicado->acefalia;
                });

                if ($request->tipo === 'item' && $duplicadoItemActivo && in_array($request->accion_duplicado, ['nuevo', 'adicionar'], true)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('duplicados', $duplicados)
                        ->with('error', 'Esta persona ya tiene un ítem activo. Usa Comisión o Reemplazar para evitar dos ítems activos.');
                }

                if ($request->tipo === 'item' && $duplicadoItemActivo && $request->accion_duplicado === 'comision') {
                    if (trim((string) $duplicadoItemActivo->numero_item) === trim((string) $request->numero_item)) {
                        return redirect()->back()
                            ->withInput()
                            ->with('duplicados', $duplicados)
                            ->with('error', 'Para registrar una comision, el item destino debe ser distinto del item titular de la persona.');
                    }

                    $itemDestinoOcupado = ServidorPublico::query()
                        ->where('tipo', 'item')
                        ->where('numero_item', $request->numero_item)
                        ->whereKeyNot($duplicadoItemActivo->id)
                        ->where(function ($query) {
                            $query->whereNull('acefalia')->orWhere('acefalia', false);
                        })
                        ->first();

                    if ($itemDestinoOcupado) {
                        return redirect()->back()
                            ->withInput()
                            ->with('duplicados', $duplicados)
                            ->with('error', 'El item destino ya tiene una persona activa. Primero debe quedar acefalia o elegirse otro item.');
                    }

                    $destinoComisionData = $this->datosPlazaDestinoComision($data);
                    $destinoComision = ServidorPublico::query()
                        ->where('tipo', 'item')
                        ->where('numero_item', $request->numero_item)
                        ->whereKeyNot($duplicadoItemActivo->id)
                        ->where('acefalia', true)
                        ->first();

                    if ($destinoComision) {
                        $destinoComision->update($destinoComisionData);
                    } else {
                        $destinoComision = ServidorPublico::create($destinoComisionData);
                    }

                    $this->registrarComisionEstructura($duplicadoItemActivo->fresh(), $destinoComision->fresh());

                    return redirect()->route('servidores.show', $destinoComision->id)
                        ->with('success', 'Comisión registrada. La persona mantiene un solo ítem activo.');
                }

                // Validar que si se elige reemplazar, se seleccione un cargo
                if ($request->accion_duplicado === 'reemplazar' && !$request->cargo_a_reemplazar) {
                    return redirect()->back()
                        ->withInput()
                        ->with('duplicados', $duplicados)
                        ->with('error', 'Debe seleccionar un cargo existente para reemplazar.');
                }

                // Manejar acción seleccionada
                if ($request->accion_duplicado === 'reemplazar' && $request->cargo_a_reemplazar && !$cargoMarcadoAcefalia) {
                    $cargoExistente = $duplicados->where('id', $request->cargo_a_reemplazar)->first();
                    if ($cargoExistente) {
                        // Marcar cargo anterior como acefalía pero conservar información del cargo
                        $cargoExistente->update([
                            'acefalia' => true,
                            'nombre' => null,
                            'apellido_paterno' => null,
                            'apellido_materno' => null,
                        ]);

                        $this->sincronizarEstructura($cargoExistente->fresh());

                        // Agregar mensaje informativo
                        session()->flash('info', "El cargo '{$cargoExistente->cargo_descripcion}' ha quedado vacante (acefalía).");
                    }

                // Si es 'nuevo', simplemente continuar sin mensajes especiales
            }

            if ($request->accion_duplicado === 'comision') {
                session()->flash('info', 'Registro guardado como comisión. El cargo anterior se mantiene activo y no se manda a acefalía.');
            }
        }
    }

        if ($request->hasFile('fotografia')) {
            $data['fotografia'] = $request->file('fotografia')->store('servidores', 'public');
        }

        $servidorCreado = ServidorPublico::create($data);

        $servidorCreado = $this->normalizarItemActivo($servidorCreado);

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

        $this->normalizarItemActivo($servidor->fresh());

        return redirect()->route('servidores.show', $servidor->id)
            ->with('success', 'Servidor actualizado correctamente.');
    }

    public function destroy($id)
    {
        $servidor = ServidorPublico::findOrFail($id);

        if ($servidor->fotografia) {
            Storage::disk('public')->delete($servidor->fotografia);
        }

        if ($servidor->asignacion) {
            $servidor->asignacion->update([
                'estado' => 'finalizada',
                'fecha_fin' => now()->toDateString(),
            ]);
        }

        $servidor->delete();

        return redirect()->route('servidores.index')
            ->with('success', 'Servidor eliminado correctamente.');
    }

    private function marcarOtrosItemsActivosComoAcefalia(ServidorPublico $servidor): void
    {
        $nombre = trim((string) $servidor->nombre);
        $apellidoPaterno = trim((string) $servidor->apellido_paterno);
        $apellidoMaterno = trim((string) $servidor->apellido_materno);

        if ($nombre === '' || $apellidoPaterno === '') {
            return;
        }

        $itemsAConvertir = ServidorPublico::query()
            ->where('tipo', 'item')
            ->whereKeyNot($servidor->id)
            ->where(function ($query) {
                $query->whereNull('acefalia')->orWhere('acefalia', false);
            })
            ->whereRaw('LOWER(nombre) = ?', [mb_strtolower($nombre, 'UTF-8')])
            ->whereRaw('LOWER(apellido_paterno) = ?', [mb_strtolower($apellidoPaterno, 'UTF-8')])
            ->whereRaw("LOWER(COALESCE(apellido_materno, '')) = ?", [mb_strtolower($apellidoMaterno, 'UTF-8')])
            ->pluck('id');

        if ($itemsAConvertir->isEmpty()) {
            return;
        }

        ServidorPublico::query()
            ->whereKey($itemsAConvertir)
            ->update([
                'acefalia' => true,
                'nombre' => null,
                'apellido_paterno' => null,
                'apellido_materno' => null,
            ]);

        ServidorPublico::query()
            ->whereKey($itemsAConvertir)
            ->get()
            ->each(function (ServidorPublico $item) {
                $this->sincronizarEstructura($item);
            });
    }

    private function datosPlazaDestinoComision(array $data): array
    {
        $data['acefalia'] = true;
        $data['nombre'] = null;
        $data['apellido_paterno'] = null;
        $data['apellido_materno'] = null;
        $data['fotografia'] = null;
        $data['cod_funcionario'] = null;
        $data['fecha_ingreso_aduana'] = null;
        $data['asignacion_familiar_desc'] = null;
        $data['asignacion_familiar_grado'] = null;
        $data['asignacion_familiar_check'] = false;
        $data['casos_especiales_desc'] = null;
        $data['casos_especiales_grado'] = null;
        $data['casos_especiales_check'] = false;
        $data['discapacidad_desc'] = null;
        $data['discapacidad_grado'] = null;
        $data['discapacidad_check'] = false;
        $data['discapacidad_tipo'] = null;
        $data['discapacidad_carnet'] = null;
        $data['discapacidad_vence'] = null;

        return $data;
    }

    private function sincronizarEstructura(ServidorPublico $servidor): void
    {
        app(EstructuraOrganizacionalService::class)->sincronizarServidor($servidor);
    }

    private function normalizarItemActivo(ServidorPublico $servidor): ServidorPublico
    {
        return app(EstructuraOrganizacionalService::class)->normalizarItemActivo($servidor);
    }

    private function registrarComisionEstructura(ServidorPublico $titular, ServidorPublico $destino): void
    {
        app(EstructuraOrganizacionalService::class)->registrarComision($titular, $destino);
    }

    /**
     * Generar reporte de servidores con items
     */
    public function reporteItems(Request $request)
    {
        return $this->streamCsvReport(
            'Reporte de Servidores Publicos con Items',
            'reporte_items',
            [
                'No.', 'UNIDAD', 'SUB-UNIDAD', 'No. ITEM', 'NOMBRE COMPLETO', 'CARGO',
                'CITE MEMO', 'COD. FUNC.', 'ESC. SALARIAL', 'DESIGNACION',
                'F. DESIGN. INICIO', 'F. DESIGN. FIN',
            ],
            $this->itemsReportQuery(),
            function (ServidorPublico $s, int $number) {
                return [
                    $number,
                    $s->unidad ?? '',
                    $s->sub_unidad ?? '',
                    $s->numero_item ?? '',
                    $this->reportFullName($s),
                    $s->cargo ?? '',
                    $s->cite_memorandum ?? '',
                    $s->cod_funcionario ?? '',
                    $s->escala_salarial ? $s->escala_salarial . ' Bs.' : '',
                    $s->designacion ?? '',
                    $this->reportDate($s->designacion_inicio),
                    $this->reportDate($s->designacion_fin),
                ];
            },
            $request
        );

        $servidores = ServidorPublico::where('tipo', 'item')
            ->where(function($query) {
                $query->whereNull('acefalia')->orWhere('acefalia', false);
            })
            ->orderBy('unidad')
            ->orderBy('sub_unidad')
            ->get();

        $headers = [
            'N°', 'UNIDAD', 'SUB-UNIDAD', 'N° ITEM', 'NOMBRE COMPLETO', 'CARGO',
            'CITE MEMO', 'COD. FUNC.', 'ESC. SALARIAL', 'DESIGNACIÓN',
            'F. DESIGN. INICIO', 'F. DESIGN. FIN'
        ];

        $data = [];
        foreach ($servidores as $s) {
            $nombreCompleto = trim(($s->nombre ?? '') . ' ' . ($s->apellido_paterno ?? '') . ' ' . ($s->apellido_materno ?? ''));
            $data[] = [
                $s->unidad ?? '',
                $s->sub_unidad ?? '',
                $s->numero_item ?? '',
                $nombreCompleto ?: '—',
                $s->cargo ?? '',
                $s->cite_memorandum ?? '',
                $s->cod_funcionario ?? '',
                $s->escala_salarial ? $s->escala_salarial . ' Bs.' : '',
                $s->designacion ?? '',
                $s->designacion_inicio ? \Carbon\Carbon::parse($s->designacion_inicio)->format('d/m/Y') : '',
                $s->designacion_fin ? \Carbon\Carbon::parse($s->designacion_fin)->format('d/m/Y') : '',
            ];
        }

        $filename = 'reporte_items_' . date('Y-m-d_H-i-s') . '.xls';

        $htmlContent = $this->generateExcelHtml(
            'Reporte de Servidores Públicos con Items',
            $headers,
            $data,
            'Total de servidores con items: ' . count($servidores)
        );

        return response($htmlContent)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

/**
 * Generar reporte de servidores con items en PDF
 */
public function reporteItemsPdf(Request $request)
{
    return $this->downloadPdfReport(
        $request,
        'Reporte de Servidores Publicos con Items',
        'items',
        'reportes.items-pdf',
        'reporte_items',
        $this->itemsReportQuery()
    );

    $servidores = ServidorPublico::where('tipo', 'item')
        ->where(function($query) {
            $query->whereNull('acefalia')->orWhere('acefalia', false);
        })
        ->orderBy('unidad')
        ->orderBy('sub_unidad')
        ->get();

    $filename = 'reporte_items_' . now()->format('Y-m-d_H-i-s') . '.pdf';

    $pdf = PDF::loadView('reportes.items-pdf', compact('servidores'));
    $pdf->setPaper('A4', 'landscape');
    $pdf->setOptions([
        'defaultFont' => 'DejaVu Sans',
        'isRemoteEnabled' => false,
        'isHtml5ParserEnabled' => true,
        'isPhpEnabled' => true,
    ]);

    return $pdf->stream($filename);
}

    /**
     * Generar reporte de servidores de consultoría en Excel
     */
    public function reporteConsultoria(Request $request)
    {
        return $this->streamCsvReport(
            'Reporte de Servidores de Consultoria',
            'reporte_consultoria',
            [
                'No.', 'UNIDAD', 'SUB-UNIDAD', 'CONTRATO', 'NOMBRE COMPLETO', 'CARGO',
                'COD. FUNC.', 'ESC. SALARIAL', 'DESIGNACION', 'F. INICIO', 'F. FIN',
            ],
            $this->consultoriaReportQuery(),
            function (ServidorPublico $s, int $number) {
                return [
                    $number,
                    $s->unidad ?? '',
                    $s->sub_unidad ?? '',
                    $s->contrato_numero ?? '',
                    $this->reportFullName($s),
                    $s->cargo_consultoria ?? '',
                    $s->cod_funcionario ?? '',
                    $s->escala_salarial ? $s->escala_salarial . ' Bs.' : '',
                    $s->designacion ?? '',
                    $this->reportDate($s->fecha_inicio_contrato),
                    $this->reportDate($s->fecha_fin_contrato),
                ];
            },
            $request
        );

        $servidores = ServidorPublico::where('tipo', 'consultoria')
            ->where(function($query) {
                $query->whereNull('acefalia')->orWhere('acefalia', false);
            })
            ->orderBy('unidad')
            ->orderBy('sub_unidad')
            ->get();

        $headers = [
            'N°', 'UNIDAD', 'SUB-UNIDAD', 'CONTRATO', 'NOMBRE COMPLETO', 'CARGO',
            'COD. FUNC.', 'ESC. SALARIAL', 'DESIGNACIÓN', 'F. INICIO', 'F. FIN'
        ];

        $data = [];
        foreach ($servidores as $s) {
            $nombreCompleto = trim(($s->nombre ?? '') . ' ' . ($s->apellido_paterno ?? '') . ' ' . ($s->apellido_materno ?? ''));
            $data[] = [
                $s->unidad ?? '',
                $s->sub_unidad ?? '',
                $s->contrato_numero ?? '',
                $nombreCompleto ?: '—',
                $s->cargo_consultoria ?? '',
                $s->cod_funcionario ?? '',
                $s->escala_salarial ? $s->escala_salarial . ' Bs.' : '',
                $s->designacion ?? '',
                $s->fecha_inicio_contrato ? \Carbon\Carbon::parse($s->fecha_inicio_contrato)->format('d/m/Y') : '',
                $s->fecha_fin_contrato ? \Carbon\Carbon::parse($s->fecha_fin_contrato)->format('d/m/Y') : '',
            ];
        }

        $filename = 'reporte_consultoria_' . date('Y-m-d_H-i-s') . '.xls';

        $htmlContent = $this->generateExcelHtml(
            'Reporte de Servidores de Consultoría',
            $headers,
            $data,
            'Total de servidores de consultoría: ' . count($servidores)
        );

        return response($htmlContent)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

/**
 * Generar reporte de servidores de consultoría en PDF
 */
public function reporteConsultoriaPdf(Request $request)
{
    return $this->downloadPdfReport(
        $request,
        'Reporte de Servidores de Consultoria',
        'consultoria',
        'reportes.consultoria-pdf',
        'reporte_consultoria',
        $this->consultoriaReportQuery()
    );

    $servidores = ServidorPublico::where('tipo', 'consultoria')
        ->where(function($query) {
            $query->whereNull('acefalia')->orWhere('acefalia', false);
        })
        ->orderBy('unidad')
        ->orderBy('sub_unidad')
        ->get();

    $filename = 'reporte_consultoria_' . now()->format('Y-m-d_H-i-s') . '.pdf';

    $pdf = PDF::loadView('reportes.consultoria-pdf', compact('servidores'));
    $pdf->setPaper('A4', 'landscape');
    $pdf->setOptions([
        'defaultFont' => 'DejaVu Sans',
        'isRemoteEnabled' => false,
        'isHtml5ParserEnabled' => true,
        'isPhpEnabled' => true,
    ]);

    return $pdf->stream($filename);
}

    /**
     * Generar reporte de acefalías en Excel
     */
    public function reporteAcefalias(Request $request)
    {
        return $this->streamCsvReport(
            'Reporte de Plazas Vacantes Acefalias',
            'reporte_acefalias',
            [
                'No.', 'TIPO', 'UNIDAD', 'SUB-UNIDAD', 'No. ITEM / CONTRATO', 'NOMBRE COMPLETO',
                'CARGO', 'COD. FUNC.', 'ESC. SALARIAL', 'DESIGNACION',
                'F. DESIGN. INICIO', 'F. DESIGN. FIN',
            ],
            $this->acefaliasReportQuery(),
            function (ServidorPublico $s, int $number) {
                return [
                    $number,
                    $s->tipo === 'item' ? 'ITEM' : 'CONSULTORIA',
                    $s->unidad ?? '',
                    $s->sub_unidad ?? '',
                    $s->tipo === 'item' ? ($s->numero_item ?? '') : ($s->contrato_numero ?? ''),
                    $this->reportFullName($s),
                    $s->tipo === 'item' ? ($s->cargo ?? '') : ($s->cargo_consultoria ?? ''),
                    $s->cod_funcionario ?? '',
                    $s->escala_salarial ? $s->escala_salarial . ' Bs.' : '',
                    $s->designacion ?? '',
                    $this->reportDate($s->designacion_inicio),
                    $this->reportDate($s->designacion_fin),
                ];
            },
            $request
        );

        $servidores = ServidorPublico::where('acefalia', true)
            ->orderBy('unidad')
            ->orderBy('sub_unidad')
            ->get();

        $headers = [
            'N°', 'TIPO', 'UNIDAD', 'SUB-UNIDAD', 'N° ITEM / CONTRATO', 'NOMBRE COMPLETO',
            'CARGO', 'COD. FUNC.', 'ESC. SALARIAL', 'DESIGNACIÓN',
            'F. DESIGN. INICIO', 'F. DESIGN. FIN'
        ];

        $data = [];
        foreach ($servidores as $s) {
            $nombreCompleto = trim(($s->nombre ?? '') . ' ' . ($s->apellido_paterno ?? '') . ' ' . ($s->apellido_materno ?? ''));
            $data[] = [
                $s->tipo === 'item' ? 'ÍTEM' : 'CONSULTORÍA',
                $s->unidad ?? '',
                $s->sub_unidad ?? '',
                $s->tipo === 'item' ? ($s->numero_item ?? '') : ($s->contrato_numero ?? ''),
                $nombreCompleto ?: '—',
                $s->tipo === 'item' ? ($s->cargo ?? '') : ($s->cargo_consultoria ?? ''),
                $s->cod_funcionario ?? '',
                $s->escala_salarial ? $s->escala_salarial . ' Bs.' : '',
                $s->designacion ?? '',
                $s->designacion_inicio ? \Carbon\Carbon::parse($s->designacion_inicio)->format('d/m/Y') : '',
                $s->designacion_fin ? \Carbon\Carbon::parse($s->designacion_fin)->format('d/m/Y') : '',
            ];
        }

        $filename = 'reporte_acefalias_' . date('Y-m-d_H-i-s') . '.xls';

        $htmlContent = $this->generateExcelHtml(
            'Reporte de Plazas Vacantes (Acefalías)',
            $headers,
            $data,
            'Total de plazas vacantes: ' . count($servidores)
        );

        return response($htmlContent)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

/**
 * Generar reporte de acefalías en PDF
 */
public function reporteAcefaliasPdf(Request $request)
{
    return $this->downloadPdfReport(
        $request,
        'Reporte de Plazas Vacantes Acefalias',
        'acefalias',
        'reportes.acefalias-pdf',
        'reporte_acefalias',
        $this->acefaliasReportQuery()
    );

    $servidores = ServidorPublico::where('acefalia', true)
        ->orderBy('unidad')
        ->orderBy('sub_unidad')
        ->get();

    $filename = 'reporte_acefalias_' . now()->format('Y-m-d_H-i-s') . '.pdf';

    $pdf = PDF::loadView('reportes.acefalias-pdf', compact('servidores'));
    $pdf->setPaper('A4', 'landscape');
    $pdf->setOptions([
        'defaultFont' => 'DejaVu Sans',
        'isRemoteEnabled' => false,
        'isHtml5ParserEnabled' => true,
        'isPhpEnabled' => true,
    ]);

    return $pdf->stream($filename);
}

    private function itemsReportQuery()
    {
        return $this->activeReportQuery()
            ->where('tipo', 'item')
            ->orderBy('unidad')
            ->orderBy('sub_unidad')
            ->orderBy('numero_item')
            ->orderBy('id');
    }

    private function consultoriaReportQuery()
    {
        return $this->activeReportQuery()
            ->where('tipo', 'consultoria')
            ->orderBy('unidad')
            ->orderBy('sub_unidad')
            ->orderBy('contrato_numero')
            ->orderBy('id');
    }

    private function acefaliasReportQuery()
    {
        return $this->reportQuery()
            ->where('acefalia', true)
            ->orderBy('unidad')
            ->orderBy('sub_unidad')
            ->orderBy('tipo')
            ->orderBy('id');
    }

    private function unidadReportQuery(string $nombreUnidad)
    {
        return $this->reportQuery()
            ->where('unidad', $nombreUnidad)
            ->orderBy('sub_unidad')
            ->orderBy('tipo')
            ->orderBy('id');
    }

    private function activeReportQuery()
    {
        return $this->reportQuery()
            ->where(function ($query) {
                $query->whereNull('acefalia')->orWhere('acefalia', false);
            });
    }

    private function reportQuery()
    {
        return ServidorPublico::query()->select([
            'id',
            'tipo',
            'nombre',
            'apellido_paterno',
            'apellido_materno',
            'unidad',
            'sub_unidad',
            'numero_item',
            'contrato_numero',
            'cargo',
            'cargo_consultoria',
            'cite_memorandum',
            'cod_funcionario',
            'escala_salarial',
            'designacion',
            'designacion_inicio',
            'designacion_fin',
            'fecha_inicio_cargo',
            'fecha_inicio_contrato',
            'fecha_fin_contrato',
            'acefalia',
            'asignacion_familiar_desc',
            'asignacion_familiar_grado',
            'casos_especiales_desc',
            'casos_especiales_grado',
        ]);
    }

    private function streamCsvReport(string $title, string $filenamePrefix, array $headers, $query, callable $rowBuilder, ?Request $request = null)
    {
        $this->prepareLargeReport();

        $filename = $filenamePrefix . '_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $total = (clone $query)->count();

        if ($request?->boolean('preview')) {
            return $this->previewHtmlReport($title, $headers, $query, $rowBuilder, $total);
        }

        return response()->streamDownload(function () use ($title, $headers, $query, $rowBuilder, $total) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [$title], ';');
            fputcsv($handle, ['Generado', now()->format('d/m/Y H:i:s')], ';');
            fputcsv($handle, ['Total registros', $total], ';');
            fputcsv($handle, [], ';');
            fputcsv($handle, $headers, ';');

            $number = 1;
            foreach ((clone $query)->lazy(self::EXPORT_CHUNK_SIZE) as $servidor) {
                fputcsv($handle, $rowBuilder($servidor, $number), ';');
                $number++;

                if ($number % self::EXPORT_CHUNK_SIZE === 0) {
                    flush();
                }
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'X-Accel-Buffering' => 'no',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    private function previewHtmlReport(string $title, array $headers, $query, callable $rowBuilder, int $total)
    {
        $limit = 100;
        $rows = [];
        $number = 1;

        foreach ((clone $query)->take($limit)->get() as $servidor) {
            $rows[] = $rowBuilder($servidor, $number);
            $number++;
        }

        return response()
            ->view('reportes.excel-preview', compact('title', 'headers', 'rows', 'total', 'limit'))
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    private function downloadPdfReport(Request $request, string $title, string $type, string $view, string $filenamePrefix, $query)
    {
        $this->prepareLargeReport();

        $total = (clone $query)->count();
        $parts = max(1, (int) ceil($total / self::PDF_PART_SIZE));

        if ($request->boolean('meta')) {
            return response()->json([
                'title' => $title,
                'type' => $type,
                'total' => $total,
                'parts' => $parts,
                'partSize' => self::PDF_PART_SIZE,
            ]);
        }

        $part = max(1, (int) $request->query('part', 1));
        $part = min($part, $parts);
        $offset = ($part - 1) * self::PDF_PART_SIZE;

        $reportOffset = $offset;
        $reportTotal = $total;
        $reportPart = $part;
        $reportParts = $parts;
        $reportTitle = $title;

        if ($request->boolean('preview')) {
            return $this->previewPdfLikeReport($title, $type, $query, $offset, $total, $part, $parts);
        }

        $servidores = (clone $query)
            ->skip($offset)
            ->take(self::PDF_PART_SIZE)
            ->get();

        $filename = $filenamePrefix . '_' . now()->format('Y-m-d_H-i-s');
        if ($parts > 1) {
            $filename .= '_parte_' . $part . '_de_' . $parts;
        }
        $filename .= '.pdf';

        $pdf = PDF::loadView($view, compact(
            'servidores',
            'reportOffset',
            'reportTotal',
            'reportPart',
            'reportParts',
            'reportTitle'
        ));

        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isRemoteEnabled' => false,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => false,
            'dpi' => 72,
            'isFontSubsettingEnabled' => true,
        ]);

        return $pdf->download($filename);
    }

    private function previewPdfLikeReport(string $title, string $type, $query, int $offset, int $total, int $part, int $parts)
    {
        $limit = 80;
        [$headers, $rowBuilder] = $this->pdfPreviewDefinition($type);
        $rows = [];
        $number = $offset + 1;

        foreach ((clone $query)->skip($offset)->take($limit)->get() as $servidor) {
            $rows[] = $rowBuilder($servidor, $number);
            $number++;
        }

        return response()
            ->view('reportes.pdf-preview', compact('title', 'headers', 'rows', 'total', 'part', 'parts', 'limit'))
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    private function pdfPreviewDefinition(string $type): array
    {
        if ($type === 'consultoria') {
            return [[
                'No.', 'UNIDAD', 'SUB-UNIDAD', 'CONTRATO', 'NOMBRE COMPLETO', 'CARGO', 'COD. FUNC.',
            ], function (ServidorPublico $s, int $number) {
                return [
                    $number,
                    $s->unidad ?? '',
                    $s->sub_unidad ?? '',
                    $s->contrato_numero ?? '',
                    $this->reportFullName($s),
                    $s->cargo_consultoria ?? '',
                    $s->cod_funcionario ?? '',
                ];
            }];
        }

        if ($type === 'acefalias') {
            return [[
                'No.', 'TIPO', 'UNIDAD', 'SUB-UNIDAD', 'ITEM / CONTRATO', 'CARGO',
            ], function (ServidorPublico $s, int $number) {
                return [
                    $number,
                    $s->tipo === 'item' ? 'ITEM' : 'CONSULTORIA',
                    $s->unidad ?? '',
                    $s->sub_unidad ?? '',
                    $s->tipo === 'item' ? ($s->numero_item ?? '') : ($s->contrato_numero ?? ''),
                    $s->tipo === 'item' ? ($s->cargo ?? '') : ($s->cargo_consultoria ?? ''),
                ];
            }];
        }

        if ($type === 'unidad') {
            return [[
                'No.', 'TIPO', 'SUB-UNIDAD', 'ITEM / CONTRATO', 'NOMBRE COMPLETO', 'CARGO',
            ], function (ServidorPublico $s, int $number) {
                return [
                    $number,
                    $s->tipo === 'item' ? 'ITEM' : 'CONSULTORIA',
                    $s->sub_unidad ?? '',
                    $s->tipo === 'item' ? ($s->numero_item ?? '') : ($s->contrato_numero ?? ''),
                    $this->reportFullName($s),
                    $s->tipo === 'item' ? ($s->cargo ?? '') : ($s->cargo_consultoria ?? ''),
                ];
            }];
        }

        return [[
            'No.', 'UNIDAD', 'SUB-UNIDAD', 'No. ITEM', 'NOMBRE COMPLETO', 'CARGO', 'COD. FUNC.',
        ], function (ServidorPublico $s, int $number) {
            return [
                $number,
                $s->unidad ?? '',
                $s->sub_unidad ?? '',
                $s->numero_item ?? '',
                $this->reportFullName($s),
                $s->cargo ?? '',
                $s->cod_funcionario ?? '',
            ];
        }];
    }

    private function prepareLargeReport(): void
    {
        @set_time_limit(300);
        @ini_set('memory_limit', '1024M');
    }

    private function reportFullName(ServidorPublico $servidor): string
    {
        $name = trim(($servidor->nombre ?? '') . ' ' . ($servidor->apellido_paterno ?? '') . ' ' . ($servidor->apellido_materno ?? ''));

        return $name !== '' ? $name : '-';
    }

    private function reportDate($value): string
    {
        if (! $value) {
            return '';
        }

        return Carbon::parse($value)->format('d/m/Y');
    }

    private function safeReportName(string $value): string
    {
        $value = strtolower(trim($value));
        $value = strtr($value, [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'ñ' => 'n',
            'Á' => 'a',
            'É' => 'e',
            'Í' => 'i',
            'Ó' => 'o',
            'Ú' => 'u',
            'Ñ' => 'n',
        ]);
        $value = preg_replace('/[^a-z0-9]+/i', '_', $value);

        return trim($value, '_') ?: 'reporte';
    }

    /**
     * Generar contenido HTML compatible con Excel
     */
    private function generateExcelHtml($title, $headers, $data, $subtitle = null)
    {
        $total = count($data);
        $dateStr = date('d/m/Y H:i:s');
        $safeTitle = htmlspecialchars($title);

        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>' . $safeTitle . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #ffffff; }
        .header {
            background: #0a1628; color: white; padding: 18px 20px; margin-bottom: 18px;
        }
        .header h1 { margin: 0; font-size: 22px; text-align: center; }
        .header .subtitle { text-align: center; font-size: 12px; margin-top: 6px; opacity: 0.85; }
        table { border-collapse: collapse; width: 100%; margin: 18px 0; }
        th {
            background: #0a1628; color: white; padding: 8px 6px; font-size: 10px;
            font-weight: bold; text-transform: uppercase; letter-spacing: 0.3px; text-align: left;
            border: 1px solid #1a3a6b;
        }
        td {
            border: 1px solid #ddd; padding: 6px; font-size: 10px; color: #333;
            vertical-align: middle;
        }
        tr:nth-child(even) td { background-color: #f8f9fa; }
        .footer {
            margin-top: 20px; text-align: center; font-size: 10px; color: #888;
            padding: 12px; border-top: 1px solid #ddd;
        }
        .footer .total { font-size: 13px; font-weight: bold; color: #0a1628; margin-bottom: 4px; }
        .num-col { text-align: center; font-weight: bold; }
        .text-muted { color: #999; font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        <h1>' . $safeTitle . '</h1>
        <div class="subtitle">' . ($subtitle ? htmlspecialchars($subtitle) : 'Gerencia Regional La Paz - Aduana Nacional de Bolivia') . '</div>
    </div>

    <table>
        <thead>
            <tr>';
        foreach ($headers as $h) {
            $html .= '<th>' . htmlspecialchars($h) . '</th>';
        }
        $html .= '</tr>
        </thead>
        <tbody>';

        $num = 0;
        foreach ($data as $row) {
            $num++;
            $html .= '<tr>';
            $html .= '<td class="num-col">' . $num . '</td>';
            foreach ($row as $cell) {
                $val = $cell === null || $cell === '' ? '<span class="text-muted">—</span>' : htmlspecialchars($cell);
                $html .= '<td>' . $val . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</tbody>
    </table>

    <div class="footer">
        <div class="total">TOTAL DE REGISTROS: ' . $total . '</div>
        Reporte generado el ' . $dateStr . ' por el Sistema de Gestión de Servidores Públicos<br>
        Gerencia Regional La Paz - Aduana Nacional de Bolivia<br>
        &copy; ' . date('Y') . ' Todos los derechos reservados
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
    public function reportePorUnidad(Request $request, $nombre)
    {
        $nombreUnidad = urldecode($nombre);

        return $this->streamCsvReport(
            'Reporte de ' . $nombreUnidad,
            'reporte_' . $this->safeReportName($nombreUnidad),
            [
                'No.', 'TIPO', 'SUB-UNIDAD', 'No. ITEM / CONTRATO', 'NOMBRE COMPLETO', 'CARGO',
                'COD. FUNC.', 'ESC. SALARIAL', 'DESIGNACION',
                'F. DESIGN. INICIO', 'F. DESIGN. FIN',
            ],
            $this->unidadReportQuery($nombreUnidad),
            function (ServidorPublico $s, int $number) {
                return [
                    $number,
                    $s->tipo === 'item' ? 'ITEM' : 'CONSULTORIA',
                    $s->sub_unidad ?? '',
                    $s->tipo === 'item' ? ($s->numero_item ?? '') : ($s->contrato_numero ?? ''),
                    $this->reportFullName($s),
                    $s->tipo === 'item' ? ($s->cargo ?? '') : ($s->cargo_consultoria ?? ''),
                    $s->cod_funcionario ?? '',
                    $s->escala_salarial ? $s->escala_salarial . ' Bs.' : '',
                    $s->designacion ?? '',
                    $this->reportDate($s->designacion_inicio),
                    $this->reportDate($s->designacion_fin),
                ];
            },
            $request
        );

        $servidores = ServidorPublico::where('unidad', $nombreUnidad)
            ->orderBy('sub_unidad')
            ->orderBy('tipo')
            ->get();

        $headers = [
            'N°', 'TIPO', 'SUB-UNIDAD', 'N° ITEM / CONTRATO', 'NOMBRE COMPLETO', 'CARGO',
            'COD. FUNC.', 'ESC. SALARIAL', 'DESIGNACIÓN',
            'F. DESIGN. INICIO', 'F. DESIGN. FIN'
        ];

        $data = [];
        foreach ($servidores as $s) {
            $nombreCompleto = trim(($s->nombre ?? '') . ' ' . ($s->apellido_paterno ?? '') . ' ' . ($s->apellido_materno ?? ''));
            $data[] = [
                $s->tipo === 'item' ? 'ÍTEM' : 'CONSULTORÍA',
                $s->sub_unidad ?? '',
                $s->tipo === 'item' ? ($s->numero_item ?? '') : ($s->contrato_numero ?? ''),
                $nombreCompleto ?: '—',
                $s->tipo === 'item' ? ($s->cargo ?? '') : ($s->cargo_consultoria ?? ''),
                $s->cod_funcionario ?? '',
                $s->escala_salarial ? $s->escala_salarial . ' Bs.' : '',
                $s->designacion ?? '',
                $s->designacion_inicio ? \Carbon\Carbon::parse($s->designacion_inicio)->format('d/m/Y') : '',
                $s->designacion_fin ? \Carbon\Carbon::parse($s->designacion_fin)->format('d/m/Y') : '',
            ];
        }

        $nombreArchivo = 'reporte_' . str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], strtolower($nombreUnidad)) . '_' . date('Y-m-d_H-i-s') . '.xls';

        $htmlContent = $this->generateExcelHtml(
            'Reporte de ' . htmlspecialchars($nombreUnidad),
            $headers,
            $data,
            'Total de registros: ' . count($servidores)
        );

        return response($htmlContent)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="' . $nombreArchivo . '"');
    }

    /**
     * Generar reporte PDF individual por unidad usando DomPDF
     */
    public function reportePorUnidadPdf(Request $request, $nombre)
    {
        // Decodificar el nombre de la unidad (URL encoded)
        $nombreUnidad = urldecode($nombre);

        return $this->downloadPdfReport(
            $request,
            'Reporte de ' . $nombreUnidad,
            'unidad',
            'reportes.unidad-pdf',
            'reporte_' . $this->safeReportName($nombreUnidad),
            $this->unidadReportQuery($nombreUnidad)
        );
        
        // Obtener todos los servidores de la unidad específica
        $servidores = ServidorPublico::where('unidad', $nombreUnidad)
            ->orderBy('sub_unidad')
            ->orderBy('tipo')
            ->get();

        // Headers para el reporte
        $headers = [
            'TIPO', 'NOMBRE', 'APELLIDO PATERNO', 'APELLIDO MATERNO', 'UNIDAD', 'SUB-UNIDAD',
            'N° ITEM', 'CARGO', 'FECHA INICIO', 'FECHA FIN',
            'COD. FUNCIONARIO', 'ESCALA SALARIAL', 'ASIGNACION FAMILIAR', 'GRADO AF',
            'CASOS ESPECIALES', 'GRADO CE'
        ];

        // Generar HTML para PDF con estilos compatibles con DomPDF
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de ' . htmlspecialchars($nombreUnidad) . ' - Servidores Públicos</title>
    <style>
        @page {
            margin: 3mm;
            size: A4;
            orientation: landscape;
        }
        
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 2px;
            background: #ffffff;
            font-size: 4px;
            line-height: 0.8;
        }
        
        .header {
            background: #1a3a6b;
            color: white;
            padding: 2px;
            text-align: center;
            margin-bottom: 2px;
            border: 1px solid #0a1628;
        }
        
        .header h1 {
            margin: 0;
            font-size: 10px;
            font-weight: bold;
        }
        
        .header .subtitle {
            margin: 1px 0 0 0;
            font-size: 6px;
            opacity: 0.9;
        }
        
        .info-cards {
            display: table;
            width: 100%;
            margin-bottom: 2px;
            border-collapse: collapse;
        }
        
        .info-card {
            display: table-cell;
            width: 33%;
            padding: 1px;
            text-align: center;
            border: 1px solid #1a3a6b;
            background: #f8f9fa;
        }
        
        .info-card h3 {
            margin: 0;
            color: #1a3a6b;
            font-size: 5px;
            font-weight: bold;
        }
        
        .info-card .value {
            font-size: 7px;
            font-weight: bold;
            color: #2c5282;
        }
        
        .table-container {
            margin-bottom: 2px;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 0;
            font-size: 3px;
            border: 1px solid #1a3a6b;
        }
        
        th { 
            background: #1a3a6b;
            color: white; 
            padding: 1px 0px; 
            text-align: center; 
            font-size: 3px;
            font-weight: bold;
            border: 1px solid #0a1628;
            white-space: nowrap;
        }
        
        td { 
            border: 1px solid #ddd; 
            padding: 0px 0px; 
            font-size: 3px;
            color: #333;
            vertical-align: middle;
            white-space: nowrap;
            text-align: center;
        }
        
        td.nombre-columna {
            font-weight: bold;
            background-color: #e8f4fd;
            text-align: left;
        }
        
        td.numero-item {
            font-weight: bold;
            text-align: center;
            background-color: #fff3cd;
        }
        
        tr:nth-child(even) { background-color: #f8f9fa; }
        tr:hover { background-color: #e3f2fd; }
        
        .footer {
            margin-top: 12px;
            text-align: center;
            color: #666;
            font-size: 7px;
            padding: 8px;
            border: 1px solid #1a3a6b;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .footer .total {
            font-size: 10px;
            font-weight: bold;
            color: #1a3a6b;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(26, 58, 107, 0.05);
            font-weight: 900;
            z-index: -1;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE SERVIDORES PÚBLICOS</h1>
        <div class="subtitle">
            ' . htmlspecialchars($nombreUnidad) . '<br>
            Sistema de Gestión de Servidores Públicos - Aduana Nacional de Bolivia
        </div>
    </div>
    
    <div class="info-cards">
        <div class="info-card">
            <h3>Total Servidores</h3>
            <div class="value">' . $servidores->count() . '</div>
        </div>
        <div class="info-card">
            <h3>Fecha Generación</h3>
            <div class="value">' . date('d/m/Y H:i:s') . '</div>
        </div>
        <div class="info-card">
            <h3>Unidad</h3>
            <div class="value" style="font-size: 8px;">' . htmlspecialchars($nombreUnidad) . '</div>
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

        foreach ($servidores as $servidor) {
            $html .= '<tr>
                <td>' . htmlspecialchars($servidor->tipo ?? '') . '</td>
                <td class="nombre-columna">' . htmlspecialchars($servidor->nombre ?? '') . '</td>
                <td class="nombre-columna">' . htmlspecialchars($servidor->apellido_paterno ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->apellido_materno ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->unidad ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->sub_unidad ?? '') . '</td>
                <td class="numero-item">' . htmlspecialchars($servidor->numero_item ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->cargo ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->fecha_inicio_cargo ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->fecha_fin_cargo ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->cod_funcionario ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->escala_salarial ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->asignacion_familiar_desc ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->asignacion_familiar_grado ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->casos_especiales_desc ?? '') . '</td>
                <td>' . htmlspecialchars($servidor->casos_especiales_grado ?? '') . '</td>
            </tr>';
        }

        $html .= '</tbody>
        </table>
    </div>
    
    <div class="footer">
        <div class="total">TOTAL DE REGISTROS: ' . $servidores->count() . '</div>
        <div>
            <strong>Reporte generado automáticamente por el Sistema de Gestión de Servidores Públicos</strong><br>
            Gerencia Regional La Paz - Aduana Nacional de Bolivia<br>
            © ' . date('Y') . ' Todos los derechos reservados
        </div>
    </div>
</body>
</html>';

        // Generar PDF con DomPDF
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'defaultFont' => 'Arial',
            'isRemoteEnabled' => false,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'dpi' => 96,
            'defaultPaperSize' => 'a4',
            'orientation' => 'landscape',
            'margin_left' => 2,
            'margin_right' => 2,
            'margin_top' => 2,
            'margin_bottom' => 2,
        ]);

        $filename = 'reporte_' . str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], strtolower($nombreUnidad)) . '_' . date('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
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
