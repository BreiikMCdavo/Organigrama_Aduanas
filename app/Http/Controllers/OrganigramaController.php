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
        $personal = ServidorPublico::where('unidad', $area)
            ->orWhere('sub_unidad', $area)
            ->get();
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

    return response()->json([
        'items'     => $items,
        'acefalias' => $acefalias,
        'cargos'    => $cargos
    ]);
}
}