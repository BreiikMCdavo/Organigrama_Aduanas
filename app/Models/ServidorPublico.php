<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServidorPublico extends Model
{
    use HasFactory;

    protected $table = 'servidores_publicos';

    protected $fillable = [
        'tipo',
        // Datos comunes
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fotografia',
        'fecha_ingreso_aduana',
        'designacion',
        // Datos ÍTEM
        'numero_item',
        'cite_memorandum',
        'cargo',
        'fecha_inicio_cargo',
        // Datos CONSULTORÍA
        'contrato_numero',
        'cargo_consultoria',
        'fecha_inicio_contrato',
        'fecha_fin_contrato',
        // Inamovilidad
        'asignacion_familiar_desc',
        'asignacion_familiar_grado',
        'casos_especiales_desc',
        'casos_especiales_grado',
        'discapacidad_desc',
        'discapacidad_grado',
    ];

    protected $casts = [
        'fecha_ingreso_aduana'  => 'date',
        'fecha_inicio_cargo'    => 'date',
        'fecha_inicio_contrato' => 'date',
        'fecha_fin_contrato'    => 'date',
    ];
}
