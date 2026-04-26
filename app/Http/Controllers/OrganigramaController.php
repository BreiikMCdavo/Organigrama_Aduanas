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

    // ITEMS
    $items = $personal->filter(fn($p) => !empty($p->numero_item))->count();

    // ACEFALIAS
    $acefalias = $personal->filter(fn($p) => empty($p->numero_item))->count();

    // AGRUPAR POR CARGO
    $cargos = $personal
        ->groupBy(function ($p) {
            return $p->cargo ?? $p->cargo_consultoria ?? 'Sin cargo';
        })
        ->map(function ($grupo) {
            return $grupo->count();
        });

    return response()->json([
        'items' => $items,
        'acefalias' => $acefalias,
        'cargos' => $cargos
    ]);
}
}