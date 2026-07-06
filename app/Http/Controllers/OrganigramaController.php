<?php

namespace App\Http\Controllers;

use App\Models\ServidorPublico;

class OrganigramaController extends Controller
{
    public function info($area)
    {
        $personal = ServidorPublico::query()
            ->forArea($area)
            ->get();

        $estadisticas = ServidorPublico::statsForArea($area);

        $cargos = $personal
            ->groupBy(function ($p) {
                return $p->cargo ?? $p->cargo_consultoria ?? 'Sin cargo';
            })
            ->map(function ($grupo) {
                return $grupo->count();
            });

        return response()->json([
            'items' => $estadisticas['items'],
            'consultoria' => $estadisticas['consultoria'],
            'acefalias' => $estadisticas['acefalias'],
            'inamoviles' => $estadisticas['inamoviles'],
            'total' => $estadisticas['total'],
            'cargos' => $cargos,
            'subunidades' => ServidorPublico::breakdownForArea($area),
        ]);
    }
}
