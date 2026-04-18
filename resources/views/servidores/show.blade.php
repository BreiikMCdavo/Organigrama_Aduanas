@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Detalle del Servidor Público</h2>
            <span class="badge {{ $servidor->tipo == 'item' ? 'bg-primary' : 'bg-success' }} fs-5">
                {{ $servidor->tipo == 'item' ? 'ITEM' : 'CONSULTORÍA' }}
            </span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @if($servidor->fotografia)
                        <img src="{{ asset('storage/' . $servidor->fotografia) }}" class="img-fluid rounded">
                    @endif
                </div>
                <div class="col-md-8">
                    <h3>{{ $servidor->nombre }} {{ $servidor->apellido_paterno }} {{ $servidor->apellido_materno }}</h3>
                    
                    @if($servidor->tipo == 'item')
                        <h4>Datos del Ítem</h4>
                        <p><strong>Nº Ítem:</strong> {{ $servidor->numero_item }}</p>
                        <p><strong>CITE Memorandum:</strong> {{ $servidor->cite_memorandum }}</p>
                        <p><strong>Cargo:</strong> {{ $servidor->cargo }}</p>
                        <p><strong>Designación:</strong> {{ $servidor->designacion }}</p>
                        <p><strong>Fecha ingreso Aduana:</strong> {{ $servidor->fecha_ingreso_aduana }}</p>
                        <p><strong>Fecha inicio cargo:</strong> {{ $servidor->fecha_inicio_cargo }}</p>
                    @else
                        <h4>Datos de Consultoría</h4>
                        <p><strong>Contrato N°:</strong> {{ $servidor->contrato_numero }}</p>
                        <p><strong>Cargo:</strong> {{ $servidor->cargo_consultoria }}</p>
                        <p><strong>Fecha ingreso Aduana:</strong> {{ $servidor->fecha_ingreso_aduana }}</p>
                        <p><strong>Fecha inicio contrato:</strong> {{ $servidor->fecha_inicio_contrato }}</p>
                        <p><strong>Fecha fin contrato:</strong> {{ $servidor->fecha_fin_contrato }}</p>
                    @endif

                    <h4>Inamovilidad</h4>
                    <p><strong>Asignación Familiar:</strong> {{ $servidor->asignacion_familiar_desc }} (Grado: {{ $servidor->asignacion_familiar_grado }})</p>
                    <p><strong>Casos especiales:</strong> {{ $servidor->casos_especiales_desc }} (Grado: {{ $servidor->casos_especiales_grado }})</p>
                    <p><strong>Discapacidad Ley N° 223:</strong> {{ $servidor->discapacidad_desc }} (Grado: {{ $servidor->discapacidad_grado }})</p>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('servidores.index') }}" class="btn btn-secondary">Volver</a>
            <a href="{{ route('servidores.edit', $servidor->id) }}" class="btn btn-warning">Editar</a>
        </div>
    </div>
</div>
@endsection