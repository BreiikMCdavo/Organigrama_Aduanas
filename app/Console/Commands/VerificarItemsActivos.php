<?php

namespace App\Console\Commands;

use App\Models\Asignacion;
use App\Models\ServidorPublico;
use Illuminate\Console\Command;

class VerificarItemsActivos extends Command
{
    protected $signature = 'organigrama:verificar-items';

    protected $description = 'Verifica duplicados activos por numero de item, nombre y titularidad estructurada.';

    public function handle(): int
    {
        $activos = ServidorPublico::query()
            ->where('tipo', 'item')
            ->where(function ($query) {
                $query->whereNull('acefalia')->orWhere('acefalia', false);
            });

        $duplicadosPorItem = (clone $activos)
            ->whereNotNull('numero_item')
            ->selectRaw('numero_item, count(*) as total')
            ->groupBy('numero_item')
            ->having('total', '>', 1)
            ->count();

        $duplicadosPorNombre = (clone $activos)
            ->whereNotNull('nombre')
            ->whereNotNull('apellido_paterno')
            ->selectRaw("LOWER(CONCAT_WS('|', nombre, apellido_paterno, COALESCE(apellido_materno, ''))) as persona_key, count(*) as total")
            ->groupBy('persona_key')
            ->having('total', '>', 1)
            ->count();

        $duplicadosTitulares = Asignacion::query()
            ->where('estado', 'activa')
            ->where('es_titular', true)
            ->whereHas('plazaItem', function ($query) {
                $query->where('tipo', 'item');
            })
            ->selectRaw('persona_id, count(*) as total')
            ->groupBy('persona_id')
            ->having('total', '>', 1)
            ->count();

        $this->info('Items activos: ' . (clone $activos)->count());
        $this->info('Duplicados activos por item: ' . $duplicadosPorItem);
        $this->info('Duplicados activos por nombre: ' . $duplicadosPorNombre);
        $this->info('Personas con mas de un item titular: ' . $duplicadosTitulares);

        return ($duplicadosPorItem === 0 && $duplicadosPorNombre === 0 && $duplicadosTitulares === 0)
            ? self::SUCCESS
            : self::FAILURE;
    }
}
