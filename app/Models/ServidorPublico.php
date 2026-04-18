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
        'nombre', 'apellido_paterno', 'apellido_materno',
        'fotografia',
        
        // Datos para ITEM
        'numero_item', 'cite_memorandum', 'cargo', 'designacion',
        'fecha_ingreso_aduana', 'fecha_inicio_cargo',
        'asignacion_familiar_grado', 'casos_especiales_grado', 
        'discapacidad_grado', 'asignacion_familiar_desc', 
        'casos_especiales_desc', 'discapacidad_desc',
        
        // Datos para CONSULTORIA
        'contrato_numero', 'cargo_consultoria', 'fecha_inicio_contrato',
        'fecha_fin_contrato',
    ];
}