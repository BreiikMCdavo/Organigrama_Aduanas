<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServidorPublico extends Model
{
    use HasFactory;

    protected $table = 'servidores_publicos';

    protected $fillable = [
        'persona_id',
        'tipo',
        'cargo',
        'numero_item',
        'fecha_ingreso'
    ];

    // 🔥 RELACIÓN con persona
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    // 🔥 RELACIÓN con unidades (CORREGIDO)
    public function unidades()
    {
        return $this->belongsToMany(
            Unidad::class,
            'servidor_unidad',
            'servidor_id',
            'unidad_id'
        );
    }
}