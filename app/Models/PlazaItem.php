<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlazaItem extends Model
{
    use HasFactory;

    protected $table = 'plazas_items';

    protected $fillable = [
        'servidor_publico_id',
        'tipo',
        'codigo_plaza',
        'numero_item',
        'contrato_numero',
        'unidad',
        'sub_unidad',
        'cargo',
        'cargo_consultoria',
        'estado',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function servidorPublico()
    {
        return $this->belongsTo(ServidorPublico::class);
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class);
    }

    public function movimientosOrigen()
    {
        return $this->hasMany(MovimientoPersonal::class, 'plaza_origen_id');
    }

    public function movimientosDestino()
    {
        return $this->hasMany(MovimientoPersonal::class, 'plaza_destino_id');
    }

    public function getCargoDescripcionAttribute(): string
    {
        return $this->tipo === 'item'
            ? ($this->cargo ?? 'Sin cargo')
            : ($this->cargo_consultoria ?? 'Sin cargo');
    }
}
