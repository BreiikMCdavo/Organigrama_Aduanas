<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        /* NUEVOS CAMPOS */
        'unidad',
        'sub_unidad',
        'acefalia',
        // Datos ÍTEM
        'numero_item',
        'cod_funcionario',
        'escala_salarial',
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
        'asignacion_familiar_check',
        'casos_especiales_desc',
        'casos_especiales_grado',
        'casos_especiales_check',
        'discapacidad_desc',
        'discapacidad_grado',
        'discapacidad_check',
        'discapacidad_tipo',
        'discapacidad_carnet',
        'discapacidad_vence',
    ];

    protected $casts = [
        'fecha_ingreso_aduana'  => 'date',
        'fecha_inicio_cargo'    => 'date',
        'fecha_inicio_contrato' => 'date',
        'fecha_fin_contrato'    => 'date',
        'designacion_inicio'    => 'date',
        'designacion_fin'       => 'date',
        'discapacidad_vence'    => 'date',
        'acefalia'              => 'boolean',
        'asignacion_familiar_check' => 'boolean',
        'casos_especiales_check'    => 'boolean',
        'discapacidad_check'        => 'boolean',
    ];

    /**
     * Buscar duplicados por nombre completo
     */
    public static function buscarPorNombreCompleto($nombre, $apellidoPaterno, $apellidoMaterno)
    {
        return self::where('nombre', trim($nombre))
            ->where('apellido_paterno', trim($apellidoPaterno))
            ->where('apellido_materno', trim($apellidoMaterno ?? ''))
            ->where(function($query) {
                $query->whereNull('acefalia')->orWhere('acefalia', false);
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

    /**
     * Obtener URL de la fotografía verificando que exista
     */
    public function getFotografiaUrlAttribute()
    {
        if (!$this->fotografia) {
            return null;
        }

        $rutaCompleta = storage_path('app/public/' . $this->fotografia);
        
        if (!file_exists($rutaCompleta)) {
            // Si el archivo no existe, limpiar la referencia
            $this->fotografia = null;
            $this->saveQuietly();
            return null;
        }

        return Storage::url($this->fotografia);
    }
}
