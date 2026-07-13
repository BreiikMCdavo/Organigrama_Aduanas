<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    use HasFactory;

    protected $table = 'asignaciones';

    protected $fillable = [
        'persona_id',
        'plaza_item_id',
        'servidor_publico_id',
        'tipo',
        'estado',
        'es_titular',
        'fecha_inicio',
        'fecha_fin',
        'observacion',
    ];

    protected $casts = [
        'es_titular' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function plazaItem()
    {
        return $this->belongsTo(PlazaItem::class);
    }

    public function servidorPublico()
    {
        return $this->belongsTo(ServidorPublico::class);
    }
}
