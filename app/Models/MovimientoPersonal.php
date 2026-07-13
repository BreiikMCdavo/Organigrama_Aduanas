<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoPersonal extends Model
{
    use HasFactory;

    protected $table = 'movimientos_personal';

    protected $fillable = [
        'persona_id',
        'plaza_origen_id',
        'plaza_destino_id',
        'servidor_publico_id',
        'tipo',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'detalle',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function plazaOrigen()
    {
        return $this->belongsTo(PlazaItem::class, 'plaza_origen_id');
    }

    public function plazaDestino()
    {
        return $this->belongsTo(PlazaItem::class, 'plaza_destino_id');
    }

    public function servidorPublico()
    {
        return $this->belongsTo(ServidorPublico::class);
    }
}
