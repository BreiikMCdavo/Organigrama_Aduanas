<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo_funcionario',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'nombre_normalizado',
        'fotografia',
        'fecha_ingreso_aduana',
        'activo',
    ];

    protected $casts = [
        'fecha_ingreso_aduana' => 'date',
        'activo' => 'boolean',
    ];

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoPersonal::class);
    }

    public function servidoresPublicos()
    {
        return $this->hasMany(ServidorPublico::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim(($this->nombre ?? '') . ' ' . ($this->apellido_paterno ?? '') . ' ' . ($this->apellido_materno ?? ''));
    }
}
