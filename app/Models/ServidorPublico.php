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
        'designacion_inicio',
        'designacion_fin',
        'interinato_inicio',
        'interinato_fin',
        'comision_inicio',
        'comision_fin',
        /* NUEVOS CAMPOS */
        'unidad',
        'sub_unidad',
        'acefalia',
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
        'designacion_inicio'    => 'date',
        'designacion_fin'       => 'date',
        'interinato_inicio'     => 'date',
        'interinato_fin'        => 'date',
        'comision_inicio'       => 'date',
        'comision_fin'          => 'date',
        'acefalia'              => 'boolean',
    ];

    /**
     * Buscar duplicados por nombre completo
     */
    public static function buscarPorNombreCompleto($nombre, $apellidoPaterno, $apellidoMaterno)
    {
        $nombreCompleto = trim(($nombre ?? '') . ' ' . ($apellidoPaterno ?? '') . ' ' . ($apellidoMaterno ?? ''));
        
        return self::where(function($query) use ($nombre, $apellidoPaterno, $apellidoMaterno) {
                $query->where('nombre', 'LIKE', '%' . trim($nombre) . '%')
                      ->where('apellido_paterno', 'LIKE', '%' . trim($apellidoPaterno) . '%')
                      ->where('apellido_materno', 'LIKE', '%' . trim($apellidoMaterno) . '%');
            })
            ->where(function($query) {
                $query->whereNull('acefalia')
                      ->orWhere('acefalia', false);
            })
            ->get();
    }

    /**
     * Obtener nombre completo formateado
     */
    public function getNombreCompletoAttribute()
    {
        return trim(($this->nombre ?? '') . ' ' . ($this->apellido_paterno ?? '') . ' ' . ($this->apellido_materno ?? ''));
    }

    /**
     * Obtener descripción del cargo según tipo
     */
    public function getCargoDescripcionAttribute()
    {
        return $this->tipo === 'item' ? ($this->cargo ?? 'Sin cargo') : ($this->cargo_consultoria ?? 'Sin cargo');
    }
}
