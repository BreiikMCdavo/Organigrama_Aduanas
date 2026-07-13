<?php

namespace App\Console\Commands;

use App\Services\EstructuraOrganizacionalService;
use Illuminate\Console\Command;

class NormalizarItemsActivos extends Command
{
    protected $signature = 'organigrama:normalizar-items';

    protected $description = 'Respeta el primer item activo por persona o numero de item y manda los posteriores a acefalia.';

    public function handle(EstructuraOrganizacionalService $estructura): int
    {
        $resultado = $estructura->normalizarItemsActivos();

        $this->info("Items revisados: {$resultado['procesados']}");
        $this->info("Items enviados a acefalia: {$resultado['convertidos']}");

        return self::SUCCESS;
    }
}
