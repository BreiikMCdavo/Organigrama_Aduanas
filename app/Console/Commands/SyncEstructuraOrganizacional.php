<?php

namespace App\Console\Commands;

use App\Models\ServidorPublico;
use App\Services\EstructuraOrganizacionalService;
use Illuminate\Console\Command;
use Throwable;

class SyncEstructuraOrganizacional extends Command
{
    protected $signature = 'organigrama:sincronizar-estructura {--dry-run : Solo cuenta registros sin guardar}';

    protected $description = 'Sincroniza servidores publicos hacia personas, plazas, asignaciones y movimientos.';

    public function handle(EstructuraOrganizacionalService $estructura): int
    {
        $query = ServidorPublico::query()->orderBy('id');

        if ($this->option('dry-run')) {
            $this->info('Registros a revisar: ' . $query->count());
            return self::SUCCESS;
        }

        $procesados = 0;
        $errores = 0;

        $query->chunkById(200, function ($servidores) use ($estructura, &$procesados, &$errores) {
            foreach ($servidores as $servidor) {
                try {
                    $estructura->sincronizarServidor($servidor);
                    $procesados++;
                } catch (Throwable $exception) {
                    $errores++;

                    if ($errores <= 10) {
                        $this->error("No se pudo sincronizar el registro {$servidor->id}: {$exception->getMessage()}");
                    } elseif ($errores === 11) {
                        $this->warn('Hay mas errores; se ocultaran para mantener legible la salida.');
                    }
                }
            }
        });

        $this->info("Estructura sincronizada: {$procesados} registros.");

        if ($errores > 0) {
            $this->warn("Registros con error: {$errores}");
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
