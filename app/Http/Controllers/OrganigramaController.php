<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServidorPublico;

class OrganigramaController extends Controller
{
    public function show($area)
    {
        $area = urldecode($area);

        $servidores = ServidorPublico::whereHas('unidades', function ($query) use ($area) {
                $query->where('nombre', $area);
            })
            ->with('persona')
            ->get();

        return response()->json([
            "items" => $servidores->count(),
            "acefalias" => 0,
            "personal" => $servidores->map(function ($s) {
                return $s->persona->nombre . ' ' . $s->persona->apellido_paterno;
            })
        ]);
    }
}