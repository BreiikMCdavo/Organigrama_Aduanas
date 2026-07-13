<?php

namespace App\Services;

use App\Models\Asignacion;
use App\Models\MovimientoPersonal;
use App\Models\Persona;
use App\Models\PlazaItem;
use App\Models\ServidorPublico;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EstructuraOrganizacionalService
{
    public function normalizarItemActivo(ServidorPublico $servidor): ServidorPublico
    {
        return DB::transaction(function () use ($servidor) {
            $servidor = $servidor->fresh() ?: $servidor;

            if ($servidor->tipo !== 'item' || $servidor->acefalia) {
                $this->sincronizarServidor($servidor);
                return $servidor->fresh() ?: $servidor;
            }

            $conflictos = $this->conflictosItemActivo($servidor)->get();

            if ($conflictos->isEmpty()) {
                $this->sincronizarServidor($servidor);
                return $servidor->fresh() ?: $servidor;
            }

            $titular = $conflictos
                ->push($servidor)
                ->sortBy('id')
                ->first();

            if ((int) $titular->id !== (int) $servidor->id) {
                $mismaPersona = $this->coincidePorNombre($titular, $servidor);

                $this->convertirServidorEnAcefalia($servidor);

                $titular = $titular->fresh();
                $servidor = $servidor->fresh();

                $this->sincronizarServidor($titular);
                $this->sincronizarServidor($servidor);
                $this->registrarMovimientoSiEsComision($titular, $servidor, $mismaPersona);

                return $servidor;
            }

            foreach ($conflictos as $conflicto) {
                $mismaPersona = $this->coincidePorNombre($titular, $conflicto);

                $this->convertirServidorEnAcefalia($conflicto);

                $conflicto = $conflicto->fresh();
                $this->sincronizarServidor($conflicto);
                $this->registrarMovimientoSiEsComision($titular->fresh(), $conflicto, $mismaPersona);
            }

            $this->sincronizarServidor($titular->fresh());

            return $titular->fresh() ?: $titular;
        });
    }

    public function normalizarItemsActivos(): array
    {
        $procesados = 0;
        $convertidos = 0;

        ServidorPublico::query()
            ->where('tipo', 'item')
            ->where(function ($query) {
                $query->whereNull('acefalia')->orWhere('acefalia', false);
            })
            ->orderBy('id')
            ->chunkById(200, function ($servidores) use (&$procesados, &$convertidos) {
                foreach ($servidores as $servidor) {
                    $procesados++;
                    $eraActivo = ! $servidor->acefalia;
                    $normalizado = $this->normalizarItemActivo($servidor);

                    if ($eraActivo && $normalizado->acefalia) {
                        $convertidos++;
                    }
                }
            });

        return [
            'procesados' => $procesados,
            'convertidos' => $convertidos,
        ];
    }

    public function sincronizarServidor(ServidorPublico $servidor): void
    {
        DB::transaction(function () use ($servidor) {
            $persona = $this->sincronizarPersona($servidor);
            $plaza = $this->sincronizarPlaza($servidor);
            $asignacion = $this->sincronizarAsignacion($servidor, $persona, $plaza);

            $this->sincronizarMovimientoComision($servidor, $persona, $plaza);

            $servidor->forceFill([
                'persona_id' => $persona?->id,
                'plaza_item_id' => $plaza?->id,
                'asignacion_id' => $asignacion?->id,
            ])->saveQuietly();
        });
    }

    public function registrarComision(ServidorPublico $titular, ServidorPublico $destino): void
    {
        DB::transaction(function () use ($titular, $destino) {
            $persona = $this->sincronizarPersona($titular);
            $plazaOrigen = $this->sincronizarPlaza($titular);
            $asignacionTitular = $this->sincronizarAsignacion($titular, $persona, $plazaOrigen);
            $plazaDestino = $this->sincronizarPlaza($destino);

            $titular->forceFill([
                'persona_id' => $persona?->id,
                'plaza_item_id' => $plazaOrigen?->id,
                'asignacion_id' => $asignacionTitular?->id,
            ])->saveQuietly();

            $destino->forceFill([
                'persona_id' => null,
                'plaza_item_id' => $plazaDestino?->id,
                'asignacion_id' => null,
            ])->saveQuietly();

            if (!$persona || !$plazaDestino) {
                return;
            }

            MovimientoPersonal::updateOrCreate(
                [
                    'persona_id' => $persona->id,
                    'plaza_destino_id' => $plazaDestino->id,
                    'servidor_publico_id' => $destino->id,
                    'tipo' => 'comision',
                ],
                [
                    'plaza_origen_id' => $plazaOrigen?->id,
                    'estado' => 'activo',
                    'fecha_inicio' => $this->fechaInicio($destino),
                    'fecha_fin' => $this->fechaFin($destino),
                    'detalle' => 'Comision registrada desde el formulario de servidores.',
                ]
            );
        });
    }

    private function sincronizarPersona(ServidorPublico $servidor): ?Persona
    {
        if ($servidor->acefalia) {
            return null;
        }

        $nombre = trim((string) $servidor->nombre);
        $apellidoPaterno = trim((string) $servidor->apellido_paterno);

        if ($nombre === '' || $apellidoPaterno === '') {
            return null;
        }

        $codigoFuncionario = trim((string) $servidor->cod_funcionario);
        $nombreNormalizado = $this->normalizarNombre(
            $servidor->nombre,
            $servidor->apellido_paterno,
            $servidor->apellido_materno
        );

        $persona = null;

        if ($codigoFuncionario !== '') {
            $persona = Persona::where('codigo_funcionario', $codigoFuncionario)->first();
        }

        if (!$persona && $nombreNormalizado !== '') {
            $persona = Persona::where('nombre_normalizado', $nombreNormalizado)->first();
        }

        $persona ??= new Persona(['nombre_normalizado' => $nombreNormalizado]);

        $persona->fill([
            'codigo_funcionario' => $codigoFuncionario !== '' ? $codigoFuncionario : $persona->codigo_funcionario,
            'nombre' => $servidor->nombre,
            'apellido_paterno' => $servidor->apellido_paterno,
            'apellido_materno' => $servidor->apellido_materno,
            'nombre_normalizado' => $nombreNormalizado,
            'fotografia' => $servidor->fotografia ?: $persona->fotografia,
            'fecha_ingreso_aduana' => $servidor->fecha_ingreso_aduana ?: $persona->fecha_ingreso_aduana,
            'activo' => true,
        ]);

        $persona->save();

        return $persona;
    }

    private function sincronizarPlaza(ServidorPublico $servidor): ?PlazaItem
    {
        $tipo = $servidor->tipo ?: 'item';
        $codigoPlaza = $this->codigoPlaza($servidor);

        $plaza = null;

        if ($codigoPlaza !== '') {
            $plaza = PlazaItem::where('tipo', $tipo)
                ->where('codigo_plaza', $codigoPlaza)
                ->first();
        }

        if (!$plaza && $servidor->plaza_item_id) {
            $plaza = PlazaItem::find($servidor->plaza_item_id);
        }

        $plaza ??= new PlazaItem();

        if ($servidor->id) {
            $plazasDelServidor = PlazaItem::where('servidor_publico_id', $servidor->id);

            if ($plaza->exists) {
                $plazasDelServidor->whereKeyNot($plaza->id);
            }

            $plazasDelServidor->update(['servidor_publico_id' => null]);
        }

        $plaza->fill([
            'servidor_publico_id' => $servidor->id,
            'tipo' => $tipo,
            'codigo_plaza' => $codigoPlaza !== '' ? $codigoPlaza : null,
            'numero_item' => $servidor->numero_item,
            'contrato_numero' => $servidor->contrato_numero,
            'unidad' => $servidor->unidad,
            'sub_unidad' => $servidor->sub_unidad,
            'cargo' => $servidor->cargo,
            'cargo_consultoria' => $servidor->cargo_consultoria,
            'estado' => $this->estadoPlaza($servidor),
            'fecha_inicio' => $this->fechaInicio($servidor),
            'fecha_fin' => $this->fechaFin($servidor),
        ]);

        $plaza->save();

        return $plaza;
    }

    private function sincronizarAsignacion(ServidorPublico $servidor, ?Persona $persona, ?PlazaItem $plaza): ?Asignacion
    {
        if (!$persona || !$plaza || $servidor->acefalia) {
            $this->finalizarAsignacionLegacy($servidor);
            return null;
        }

        $tipoAsignacion = $this->tipoAsignacion($servidor);
        $esTitular = $servidor->tipo === 'item' && $tipoAsignacion !== 'comision';

        if ($esTitular) {
            $this->finalizarOtrosItemsTitulares($persona, $servidor);
        }

        $asignacion = null;

        if ($servidor->asignacion_id) {
            $asignacion = Asignacion::find($servidor->asignacion_id);
        }

        $asignacion ??= Asignacion::where('servidor_publico_id', $servidor->id)->first();
        $asignacion ??= new Asignacion();

        $asignacion->fill([
            'persona_id' => $persona->id,
            'plaza_item_id' => $plaza->id,
            'servidor_publico_id' => $servidor->id,
            'tipo' => $tipoAsignacion,
            'estado' => 'activa',
            'es_titular' => $esTitular,
            'fecha_inicio' => $this->fechaInicio($servidor),
            'fecha_fin' => $this->fechaFin($servidor),
            'observacion' => $servidor->designacion,
        ]);

        $asignacion->save();

        return $asignacion;
    }

    private function sincronizarMovimientoComision(ServidorPublico $servidor, ?Persona $persona, ?PlazaItem $plaza): void
    {
        if (!$persona || !$plaza || !$this->tieneDesignacion($servidor, 'comision')) {
            return;
        }

        $plazaOrigen = $this->plazaTitularActiva($persona, $plaza);

        MovimientoPersonal::updateOrCreate(
            [
                'persona_id' => $persona->id,
                'plaza_destino_id' => $plaza->id,
                'servidor_publico_id' => $servidor->id,
                'tipo' => 'comision',
            ],
            [
                'plaza_origen_id' => $plazaOrigen?->id,
                'estado' => 'activo',
                'fecha_inicio' => $this->fechaInicio($servidor),
                'fecha_fin' => $this->fechaFin($servidor),
                'detalle' => 'Comision detectada por designacion.',
            ]
        );
    }

    private function conflictosItemActivo(ServidorPublico $servidor)
    {
        $numeroItem = trim((string) $servidor->numero_item);
        $nombreValido = trim((string) $servidor->nombre) !== ''
            && trim((string) $servidor->apellido_paterno) !== '';

        return ServidorPublico::query()
            ->where('tipo', 'item')
            ->whereKeyNot($servidor->id)
            ->where(function ($query) {
                $query->whereNull('acefalia')->orWhere('acefalia', false);
            })
            ->where(function ($query) use ($servidor, $numeroItem, $nombreValido) {
                if ($numeroItem !== '') {
                    $query->where('numero_item', $numeroItem);
                }

                if ($nombreValido) {
                    $method = $numeroItem !== '' ? 'orWhere' : 'where';

                    $query->{$method}(function ($nombreQuery) use ($servidor) {
                        $nombreQuery
                            ->whereRaw('LOWER(nombre) = ?', [mb_strtolower(trim((string) $servidor->nombre), 'UTF-8')])
                            ->whereRaw('LOWER(apellido_paterno) = ?', [mb_strtolower(trim((string) $servidor->apellido_paterno), 'UTF-8')])
                            ->whereRaw("LOWER(COALESCE(apellido_materno, '')) = ?", [mb_strtolower(trim((string) $servidor->apellido_materno), 'UTF-8')]);
                    });
                }

                if ($numeroItem === '' && ! $nombreValido) {
                    $query->whereRaw('1 = 0');
                }
            })
            ->orderBy('id');
    }

    private function convertirServidorEnAcefalia(ServidorPublico $servidor): void
    {
        $servidor->update([
            'acefalia' => true,
            'nombre' => null,
            'apellido_paterno' => null,
            'apellido_materno' => null,
            'fotografia' => null,
            'cod_funcionario' => null,
            'fecha_ingreso_aduana' => null,
            'asignacion_familiar_desc' => null,
            'asignacion_familiar_grado' => null,
            'asignacion_familiar_check' => false,
            'casos_especiales_desc' => null,
            'casos_especiales_grado' => null,
            'casos_especiales_check' => false,
            'discapacidad_desc' => null,
            'discapacidad_grado' => null,
            'discapacidad_check' => false,
            'discapacidad_tipo' => null,
            'discapacidad_carnet' => null,
            'discapacidad_vence' => null,
            'persona_id' => null,
            'asignacion_id' => null,
        ]);
    }

    private function registrarMovimientoSiEsComision(ServidorPublico $titular, ServidorPublico $destino, bool $mismaPersona): void
    {
        if (! $mismaPersona || ! $this->tieneDesignacion($destino, 'comision')) {
            return;
        }

        if (trim((string) $titular->numero_item) === trim((string) $destino->numero_item)) {
            return;
        }

        $this->registrarComision($titular, $destino);
    }

    private function coincidePorNombre(ServidorPublico $a, ServidorPublico $b): bool
    {
        return $this->normalizarNombre($a->nombre, $a->apellido_paterno, $a->apellido_materno) !== ''
            && $this->normalizarNombre($a->nombre, $a->apellido_paterno, $a->apellido_materno)
                === $this->normalizarNombre($b->nombre, $b->apellido_paterno, $b->apellido_materno);
    }

    private function finalizarAsignacionLegacy(ServidorPublico $servidor): void
    {
        if (!$servidor->asignacion_id) {
            return;
        }

        $asignacion = Asignacion::find($servidor->asignacion_id);

        if (!$asignacion) {
            return;
        }

        $asignacion->fill([
            'estado' => 'finalizada',
            'fecha_fin' => $this->fechaFin($servidor) ?: now()->toDateString(),
        ])->save();
    }

    private function finalizarOtrosItemsTitulares(Persona $persona, ServidorPublico $servidor): void
    {
        Asignacion::where('persona_id', $persona->id)
            ->where('estado', 'activa')
            ->where('es_titular', true)
            ->where('servidor_publico_id', '!=', $servidor->id)
            ->whereHas('plazaItem', function ($query) {
                $query->where('tipo', 'item');
            })
            ->update([
                'estado' => 'finalizada',
                'fecha_fin' => now()->toDateString(),
            ]);
    }

    private function plazaTitularActiva(Persona $persona, PlazaItem $plazaActual): ?PlazaItem
    {
        $asignacion = Asignacion::with('plazaItem')
            ->where('persona_id', $persona->id)
            ->where('estado', 'activa')
            ->where('es_titular', true)
            ->where('plaza_item_id', '!=', $plazaActual->id)
            ->whereHas('plazaItem', function ($query) {
                $query->where('tipo', 'item');
            })
            ->latest('id')
            ->first();

        return $asignacion?->plazaItem;
    }

    private function tipoAsignacion(ServidorPublico $servidor): string
    {
        if ($this->tieneDesignacion($servidor, 'comision')) {
            return 'comision';
        }

        if ($servidor->tipo === 'consultoria') {
            return 'consultoria';
        }

        if ($this->tieneDesignacion($servidor, 'interinato')) {
            return 'interinato';
        }

        if ($this->tieneDesignacion($servidor, 'designacion')) {
            return 'designacion';
        }

        return 'titular';
    }

    private function estadoPlaza(ServidorPublico $servidor): string
    {
        if ($servidor->acefalia) {
            return $this->tieneDesignacion($servidor, 'comision')
                ? 'cubierta_temporal'
                : 'acefalia';
        }

        if ($this->tieneDesignacion($servidor, 'comision')) {
            return 'cubierta_temporal';
        }

        return 'ocupada';
    }

    private function codigoPlaza(ServidorPublico $servidor): string
    {
        $codigo = $servidor->tipo === 'consultoria'
            ? $servidor->contrato_numero
            : $servidor->numero_item;

        return trim((string) $codigo);
    }

    private function fechaInicio(ServidorPublico $servidor)
    {
        return $servidor->designacion_inicio
            ?: $servidor->fecha_inicio_cargo
            ?: $servidor->fecha_inicio_contrato;
    }

    private function fechaFin(ServidorPublico $servidor)
    {
        return $servidor->designacion_fin
            ?: $servidor->fecha_fin_contrato;
    }

    private function tieneDesignacion(ServidorPublico $servidor, string $valor): bool
    {
        $texto = $this->normalizarTexto((string) $servidor->designacion);

        return str_contains($texto, $valor);
    }

    private function normalizarNombre(?string $nombre, ?string $apellidoPaterno, ?string $apellidoMaterno): string
    {
        return $this->normalizarTexto(trim(
            (string) $nombre . ' ' . (string) $apellidoPaterno . ' ' . (string) $apellidoMaterno
        ));
    }

    private function normalizarTexto(string $texto): string
    {
        $texto = Str::ascii($texto);
        $texto = mb_strtolower($texto, 'UTF-8');
        $texto = preg_replace('/\s+/', ' ', $texto) ?? $texto;

        return trim($texto);
    }
}
