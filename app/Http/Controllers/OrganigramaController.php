<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServidorPublico;

class OrganigramaController extends Controller
{
    public function show($area)
    {
        
        // Decodificar por si viene con espacios o %
        $area = urldecode($area);

        // 🔥 FILTRAR por área (AJUSTA ESTE CAMPO A TU BD REAL)
        $servidores = ServidorPublico::where('area_administracion', $area)
            ->get();

        return response()->json([
            "items" => $servidores->count(),
            "acefalias" => 0, // si no tienes campo aún
            "personal" => $servidores->map(function ($s) {
                return $s->nombre . ' ' . $s->apellido_paterno;
            })
        ]);
    }
}