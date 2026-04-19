<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    use HasFactory;

    protected $table = 'unidades';

    protected $fillable = [
        'nombre',
        'tipo'
    ];

    // 🔥 RELACIÓN inversa
    public function servidores()
    {
        return $this->belongsToMany(
            ServidorPublico::class,
            'servidor_unidad',
            'unidad_id',
            'servidor_id'
        );
    }
}