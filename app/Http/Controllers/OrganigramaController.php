<?php

namespace App\Http\Controllers;

use App\Models\ServidorPublico;
use Illuminate\Http\Request;

class OrganigramaController extends Controller
{
  public function info($area)
{
    // GERENCIA = todos
    if ($area === 'GERENCIA REGIONAL LA PAZ - GRLPZ') {
        $personal = ServidorPublico::all();
    } else {
        // Si es GERENCIA, incluir todas sus sub-unidades
        if ($area === 'GERENCIA REGIONAL LA PAZ - GRLPZ') {
            $subUnidadesGerencia = [
                'GERENTE', 'ASESORÍA', 'SECRETARIA', 'SISTEMAS', 
                'USO', 'ARCHIVO', 'Unidad Administrativa', 
                'Unidad Fiscalización', 'Unidad Jurídica',
                'Administración Aduana Interior La Paz',
                'Aduana Frontera Guayaramerín',
                'Aduana Aeropuerto El Alto',
                'Administración Aduana Zona Franca Industrial Patacamaya',
                'Administración Aduana Frontera Desaguadero',
                'Zona Franca Comercial / Frontera Cobija',
                'Agencia Aduana Exterior Matarani',
                'Administración Aduana Frontera Charaña'
            ];
            
            $personal = ServidorPublico::where('unidad', $area)
                ->orWhereIn('sub_unidad', $subUnidadesGerencia)
                ->get();
        } else {
            $personal = ServidorPublico::where('unidad', $area)
                ->orWhere('sub_unidad', $area)
                ->get();
        }
    }

    // OBTENER TODOS LOS REGISTROS PARA VERIFICAR DUPLICADOS POR NOMBRE COMPLETO
    $todosLosRegistros = ServidorPublico::all();
    
    $itemsValidos = 0;
    $acefaliasPorDuplicado = 0;

    foreach ($personal as $persona) {
        // Solo considerar personas con nombre (no acefalías vacías)
        if (empty(trim($persona->nombre ?? '')) || $persona->acefalia) {
            if ($persona->acefalia) {
                $acefaliasPorDuplicado++; // Contar acefalías existentes
            }
            continue;
        }

        $nombreCompleto = trim(($persona->nombre ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''));
        
        // Buscar todos los cargos de esta persona en TODA la base de datos
        $todosSusCargos = $todosLosRegistros->filter(function($p) use ($nombreCompleto) {
            $nombreCompletoP = trim(($p->nombre ?? '') . ' ' . ($p->apellido_paterno ?? '') . ' ' . ($p->apellido_materno ?? ''));
            return $nombreCompletoP === $nombreCompleto && !$p->acefalia;
        })->sortBy('created_at');

        if ($todosSusCargos->count() === 1) {
            // Es su único cargo en toda la base de datos
            if ($persona->tipo === 'item') {
                $itemsValidos++;
            }
        } else {
            // Tiene múltiples cargos, verificar si este es el primero
            $primerCargo = $todosSusCargos->first();
            if ($primerCargo->id === $persona->id) {
                // Este es su primer cargo, cuenta como item
                if ($persona->tipo === 'item') {
                    $itemsValidos++;
                }
            } else {
                // Este es un cargo adicional, cuenta como acefalía
                $acefaliasPorDuplicado++;
            }
        }
    }

    // ITEMS y ACEFALIAS finales
    $items = $itemsValidos;
    $acefalias = $acefaliasPorDuplicado;

    // AGRUPAR POR CARGO
    $cargos = $personal
        ->groupBy(function ($p) {
            return $p->cargo ?? $p->cargo_consultoria ?? 'Sin cargo';
        })
        ->map(function ($grupo) {
            return $grupo->count();
        });

    // CALCULAR INAMOVILES CON DEPURACIÓN
    $inamoviles = 0;
    $encontrados = [];
    
    foreach ($personal as $persona) {
        // Verificar si tiene algún campo de inamovilidad lleno
        $tieneInamovilidad = false;
        $campos = [];
        
        // Revisar cada campo de inamovilidad
        if (!empty($persona->asignacion_familiar_desc) && trim($persona->asignacion_familiar_desc) !== '') {
            $tieneInamovilidad = true;
            $campos[] = 'asignacion_familiar';
        }
        if (!empty($persona->casos_especiales_desc) && trim($persona->casos_especiales_desc) !== '') {
            $tieneInamovilidad = true;
            $campos[] = 'casos_especiales';
        }
        if (!empty($persona->discapacidad_desc) && trim($persona->discapacidad_desc) !== '') {
            $tieneInamovilidad = true;
            $campos[] = 'discapacidad';
        }
        
        // Si tiene inamovilidad, nombre y no es acefalía, contar
        if ($tieneInamovilidad && 
            !empty($persona->nombre) && 
            trim($persona->nombre) !== '' && 
            !$persona->acefalia) {
            $inamoviles++;
            $encontrados[] = [
                'nombre' => $persona->nombre,
                'unidad' => $persona->unidad,
                'sub_unidad' => $persona->sub_unidad,
                'campos' => $campos
            ];
        }
    }

    return response()->json([
        'items'     => $items,
        'acefalias' => $acefalias,
        'inamoviles' => $inamoviles,
        'cargos'    => $cargos,
        'debug' => [
            'total_personas' => count($personal),
            'encontrados_inamoviles' => $encontrados
        ]
    ]);
}
}