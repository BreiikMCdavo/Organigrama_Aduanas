<?php

namespace Database\Seeders;

use App\Models\ServidorPublico;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoServidoresPublicosSeeder extends Seeder
{
    private const TOTAL = 2000;

    private array $unidades = [
        'GERENCIA REGIONAL LA PAZ - GRLPZ' => ['GERENTE', 'ASESORÍA', 'SECRETARIA', 'SISTEMAS', 'ARCHIVO', 'USO'],
        'Unidad Administrativa' => ['Administración', 'Activos Fijos', 'Contrataciones', 'Servicios Generales', 'Talento Humano', 'Contabilidad', 'Finanzas / Tesorería y Archivo'],
        'Unidad Fiscalización' => ['Control', 'Controles diferidos', 'Fiscalizaciones posteriores', 'Operaciones'],
        'Unidad Jurídica' => ['Cobranza coactiva', 'Procesos administrativos', 'Procurador Jurídica', 'Técnica jurídica'],
        'Administración Aduana Interior La Paz' => ['Administración', 'Despachos', 'Disposición de mercancías', 'Gestión', 'SPCC (Comisos)'],
        'Administración Aduana Aeropuerto' => ['Administración', 'Carga Aérea', 'Equipajes', 'Control', 'Despachos', 'Archivo'],
        'Administración Aduana Frontera Desaguadero' => ['CEBAF', 'Control Fronterizo', 'Puente Nuevo', 'Despachos', 'Gestión'],
        'Administración Aduana Frontera Guayaramerín' => ['Gestión Aduanera / Operativa Guayaramerín', 'Despachos', 'Control'],
        'Administración Aduana Frontera Cobija' => ['Despachos Frontera Cobija', 'Control', 'Disposición de mercancías'],
        'Administración Aduana Zona Franca Patacamaya' => ['Despachos', 'Gestión', 'Control'],
        'Administración Aduana Frontera Charaña' => ['Gestión', 'Tránsitos', 'Control Fronterizo'],
        'Administración Agencia Aduana Exterior Matarani' => ['Disposición Exterior Matarani', 'Despachos', 'Gestión'],
    ];

    private array $nombres = [
        'Adrian', 'Alejandro', 'Alicia', 'Andrea', 'Angela', 'Antonio', 'Beatriz', 'Brenda', 'Camila', 'Carlos',
        'Carolina', 'Cecilia', 'Claudia', 'Cristian', 'Daniel', 'Daniela', 'Diego', 'Edgar', 'Elena', 'Erika',
        'Esteban', 'Fernando', 'Gabriela', 'Gonzalo', 'Hector', 'Hugo', 'Ivan', 'Javier', 'Jhoana', 'Jorge',
        'Jose', 'Juan', 'Julio', 'Karen', 'Laura', 'Leonardo', 'Lidia', 'Lizeth', 'Luis', 'Marcelo',
        'Maria', 'Maribel', 'Mario', 'Martha', 'Miguel', 'Natalia', 'Oscar', 'Patricia', 'Paola', 'Raul',
        'Rene', 'Rocio', 'Sandra', 'Silvia', 'Sofia', 'Veronica', 'Victor', 'Wendy', 'Ximena', 'Yolanda',
    ];

    private array $apellidos = [
        'Aguilar', 'Alarcon', 'Alvarez', 'Apaza', 'Arias', 'Arze', 'Ayala', 'Bautista', 'Blanco', 'Calle',
        'Camacho', 'Campos', 'Canaviri', 'Cardenas', 'Castillo', 'Choque', 'Condori', 'Contreras', 'Copa', 'Cruz',
        'Delgado', 'Diaz', 'Espinoza', 'Fernandez', 'Flores', 'Fuentes', 'Garcia', 'Gomez', 'Gonzales', 'Gutierrez',
        'Herrera', 'Huanca', 'Mamani', 'Martinez', 'Medina', 'Mendoza', 'Molina', 'Morales', 'Moya', 'Ortega',
        'Paredes', 'Perez', 'Quispe', 'Ramirez', 'Ramos', 'Rios', 'Rivera', 'Rocha', 'Rodriguez', 'Rojas',
        'Salazar', 'Sanchez', 'Silva', 'Suarez', 'Tapia', 'Torrez', 'Vargas', 'Vasquez', 'Vega', 'Villca',
    ];

    private array $cargosItem = [
        'Administrador Aduanero', 'Analista de Gestion Aduanera', 'Auxiliar Administrativo', 'Auxiliar de Archivo',
        'Fiscalizador Aduanero', 'Inspector de Control', 'Jefe de Unidad', 'Operador de Sistemas',
        'Profesional Aduanero', 'Profesional en Contrataciones', 'Profesional en Finanzas', 'Profesional Juridico',
        'Responsable Administrativo Financiero', 'Supervisor de Despachos', 'Tecnico Administrativo',
        'Tecnico de Control Diferido', 'Tecnico en Activos Fijos', 'Tecnico en Gestion Aduanera',
    ];

    private array $cargosConsultoria = [
        'Consultor de apoyo administrativo', 'Consultor de control documental', 'Consultor en sistemas',
        'Consultor juridico', 'Consultor tecnico aduanero', 'Consultor operativo',
    ];

    public function run(): void
    {
        DB::table('servidores_publicos')
            ->where('cod_funcionario', 'like', 'DEMO-%')
            ->delete();

        $rows = [];
        $now = now();
        $itemNumber = 5000;

        for ($i = 1; $i <= self::TOTAL; $i++) {
            [$unidad, $subUnidad] = $this->areaFor($i);

            $isConsultoria = $i % 13 === 0;
            $isAcefalia = !$isConsultoria && $i % 9 === 0;
            $hasInamovilidad = !$isAcefalia && $i % 7 === 0;
            $inicio = Carbon::create(2018 + ($i % 8), (($i % 12) + 1), (($i % 25) + 1));
            $designacionFin = $i % 11 === 0 ? $inicio->copy()->addMonths(6 + ($i % 18)) : null;

            $row = [
                'tipo' => $isConsultoria ? 'consultoria' : 'item',
                'nombre' => $isAcefalia ? null : $this->nombres[$i % count($this->nombres)],
                'apellido_paterno' => $isAcefalia ? null : $this->apellidos[($i * 3) % count($this->apellidos)],
                'apellido_materno' => $isAcefalia ? null : $this->apellidos[($i * 7) % count($this->apellidos)],
                'fotografia' => null,
                'fecha_ingreso_aduana' => $isAcefalia ? null : $inicio->toDateString(),
                'designacion' => $this->designacionFor($i),
                'designacion_inicio' => $inicio->toDateString(),
                'designacion_fin' => $designacionFin?->toDateString(),
                'unidad' => $unidad,
                'sub_unidad' => $subUnidad,
                'acefalia' => $isAcefalia,
                'numero_item' => $isConsultoria ? null : (string) ($itemNumber + $i),
                'cod_funcionario' => 'DEMO-' . str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'escala_salarial' => (string) (4200 + (($i % 18) * 350)),
                'cite_memorandum' => $isConsultoria ? null : 'DEMO/GRLPZ/' . str_pad((string) $i, 5, '0', STR_PAD_LEFT) . '/2026',
                'cargo' => $isConsultoria ? null : $this->cargosItem[$i % count($this->cargosItem)],
                'fecha_inicio_cargo' => $isConsultoria || $isAcefalia ? null : $inicio->toDateString(),
                'contrato_numero' => $isConsultoria ? 'CD-DEMO-' . str_pad((string) $i, 5, '0', STR_PAD_LEFT) : null,
                'cargo_consultoria' => $isConsultoria ? $this->cargosConsultoria[$i % count($this->cargosConsultoria)] : null,
                'fecha_inicio_contrato' => $isConsultoria ? $inicio->toDateString() : null,
                'fecha_fin_contrato' => $isConsultoria ? $inicio->copy()->addMonths(8 + ($i % 6))->toDateString() : null,
                'asignacion_familiar_desc' => $hasInamovilidad && $i % 2 === 0 ? 'Inamovilidad por asignacion familiar' : null,
                'asignacion_familiar_grado' => $hasInamovilidad && $i % 2 === 0 ? 'AF-' . (($i % 4) + 1) : null,
                'asignacion_familiar_check' => $hasInamovilidad && $i % 2 === 0,
                'casos_especiales_desc' => $hasInamovilidad && $i % 3 === 0 ? 'Caso especial documentado' : null,
                'casos_especiales_grado' => $hasInamovilidad && $i % 3 === 0 ? 'CE-' . (($i % 3) + 1) : null,
                'casos_especiales_check' => $hasInamovilidad && $i % 3 === 0,
                'discapacidad_desc' => $hasInamovilidad && $i % 5 === 0 ? 'Discapacidad registrada Ley 223' : null,
                'discapacidad_grado' => $hasInamovilidad && $i % 5 === 0 ? 'MOD' : null,
                'discapacidad_check' => $hasInamovilidad && $i % 5 === 0,
                'discapacidad_tipo' => $hasInamovilidad && $i % 5 === 0 ? 'Fisica' : null,
                'discapacidad_carnet' => $hasInamovilidad && $i % 5 === 0 ? 'DIS-DEMO-' . str_pad((string) $i, 5, '0', STR_PAD_LEFT) : null,
                'discapacidad_vence' => $hasInamovilidad && $i % 5 === 0 ? $inicio->copy()->addYears(4)->toDateString() : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $rows[] = $row;

            if (count($rows) === 250) {
                ServidorPublico::insert($rows);
                $rows = [];
            }
        }

        if ($rows) {
            ServidorPublico::insert($rows);
        }
    }

    private function areaFor(int $index): array
    {
        $unidades = array_keys($this->unidades);
        $unidad = $unidades[$index % count($unidades)];
        $subUnidades = $this->unidades[$unidad];

        return [$unidad, $subUnidades[($index * 5) % count($subUnidades)]];
    }

    private function designacionFor(int $index): string
    {
        return match ($index % 4) {
            0 => 'Designacion',
            1 => 'Interinato',
            2 => 'Comision',
            default => 'Designacion, Comision',
        };
    }
}
