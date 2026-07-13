<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ServidorPublico extends Model
{
    use HasFactory;

    public const GERENCIA_AREA = 'GERENCIA REGIONAL LA PAZ - GRLPZ';

    protected $table = 'servidores_publicos';

    protected $fillable = [
        'persona_id',
        'plaza_item_id',
        'asignacion_id',
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

    public function scopeForArea($query, ?string $area)
    {
        $area = trim($area ?? '');

        if ($area === '' || $area === self::GERENCIA_AREA) {
            return $query;
        }

        return $query->where(function ($q) use ($area) {
            $q->where('unidad', $area)
                ->orWhere('sub_unidad', $area);
        });
    }

    public function scopeSearch($query, ?string $term)
    {
        $term = trim($term ?? '');

        if ($term === '') {
            return $query;
        }

        $like = '%' . $term . '%';

        return $query->where(function ($q) use ($like) {
            $q->where('numero_item', 'like', $like)
                ->orWhere('contrato_numero', 'like', $like)
                ->orWhere('nombre', 'like', $like)
                ->orWhere('apellido_paterno', 'like', $like)
                ->orWhere('apellido_materno', 'like', $like)
                ->orWhereRaw("CONCAT_WS(' ', nombre, apellido_paterno, apellido_materno) like ?", [$like]);
        });
    }

    public static function statsForArea(?string $area): array
    {
        return self::statsFromRecords(
            self::query()->forArea($area)->get()
        );
    }

    public static function breakdownForArea(?string $area)
    {
        $area = trim($area ?? '');
        $records = self::query()->forArea($area)->get();
        $groupColumn = ($area === '' || $area === self::GERENCIA_AREA) ? 'unidad' : 'sub_unidad';

        return $records
            ->filter(function ($record) use ($groupColumn) {
                return trim((string) $record->{$groupColumn}) !== '';
            })
            ->groupBy($groupColumn)
            ->map(function ($group, $name) {
                return array_merge(['nombre' => $name], self::statsFromRecords($group));
            })
            ->sortBy('nombre')
            ->values();
    }

    private static function statsFromRecords($records): array
    {
        $isActive = function ($record) {
            return !$record->acefalia;
        };

        $hasInamovilidad = function ($record) use ($isActive) {
            if (!$isActive($record)) {
                return false;
            }

            return trim((string) $record->asignacion_familiar_desc) !== ''
                || trim((string) $record->casos_especiales_desc) !== ''
                || trim((string) $record->discapacidad_desc) !== '';
        };

        return [
            'items' => $records->filter(fn ($record) => $record->tipo === 'item' && $isActive($record))->count(),
            'consultoria' => $records->filter(fn ($record) => $record->tipo === 'consultoria' && $isActive($record))->count(),
            'inamoviles' => $records->filter($hasInamovilidad)->count(),
            'acefalias' => $records->filter(fn ($record) => (bool) $record->acefalia)->count(),
            'total' => $records->count(),
        ];
    }

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

    public function getFotografiaUrlAttribute()
    {
        if (!$this->fotografia) {
            return null;
        }

        $rutaCompleta = storage_path('app/public/' . $this->fotografia);

        if (!file_exists($rutaCompleta)) {
            return null;
        }

        return asset('storage/' . $this->fotografia);
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function plazaItem()
    {
        return $this->belongsTo(PlazaItem::class);
    }

    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class);
    }
}
