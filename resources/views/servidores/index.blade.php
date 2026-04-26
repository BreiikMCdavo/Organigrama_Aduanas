@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Servidores Públicos Registrados</h5>
        <a href="{{ route('servidores.create') }}" class="btn btn-primary">+ Agregar Servidor Público</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @forelse($servidores as $servidor)
    <div class="card mb-4 shadow-sm border-0" style="border-radius:12px;">
        <div class="card-body p-4">
            <div class="row align-items-center">

                {{-- Foto --}}
                <div class="col-md-auto text-center mb-3 mb-md-0">
                    @if($servidor->fotografia)
                        <img src="{{ asset('storage/' . $servidor->fotografia) }}" class="rounded-circle"
                             style="width:80px;height:80px;object-fit:cover;border:3px solid #1565c0;box-shadow:0 2px 8px rgba(0,0,0,0.15);">
                    @else
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
                             style="width:80px;height:80px;font-size:2rem;flex-shrink:0;">👤</div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="col-md">
                    <div class="mb-2">
                        <h5 class="mb-1 fw-bold" style="font-size:1.1rem;">
                            {{ $servidor->nombre }} {{ $servidor->apellido_paterno }} {{ $servidor->apellido_materno }}
                        </h5>
                        @if($servidor->acefalia)
                            <span class="badge bg-warning text-dark px-3 py-1">ACEFALÍA</span>
                        @elseif($servidor->tipo === 'item')
                            <span class="badge bg-primary px-3 py-1">ÍTEM</span>
                        @else
                            <span class="badge bg-success px-3 py-1">CONSULTORÍA</span>
                        @endif
                    </div>

                    <div class="mb-2 text-muted">
                        @if($servidor->tipo === 'item')
                            <div><strong>N° Ítem:</strong> {{ $servidor->numero_item }}</div>
                            <div><strong>Cargo:</strong> {{ $servidor->cargo }}</div>
                        @else
                            <div><strong>Contrato:</strong> {{ $servidor->contrato_numero }}</div>
                            <div><strong>Cargo:</strong> {{ $servidor->cargo_consultoria }}</div>
                        @endif
                        @if($servidor->designacion)
                            <div><strong>Designación:</strong> {{ $servidor->designacion }}</div>
                        @endif
                    </div>

                    <div class="text-muted small">
                        @if($servidor->unidad)
                            <strong>Unidad:</strong> {{ $servidor->unidad }}
                            @if($servidor->sub_unidad) / {{ $servidor->sub_unidad }} @endif
                            &bull;
                        @endif
                        <strong>Ingreso:</strong> {{ $servidor->fecha_ingreso_aduana ? \Carbon\Carbon::parse($servidor->fecha_ingreso_aduana)->format('d/m/Y') : '—' }}
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="col-md-auto text-center">
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('servidores.show', $servidor->id) }}" class="btn btn-sm btn-primary px-3">Ver</a>
                        <a href="{{ route('servidores.edit', $servidor->id) }}" class="btn btn-sm btn-warning px-3">Editar</a>
                        <button type="button" class="btn btn-sm btn-danger px-3"
                                onclick="confirmarEliminar({{ $servidor->id }}, '{{ addslashes($servidor->nombre.' '.$servidor->apellido_paterno) }}')">
                            Eliminar
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @empty
        <div class="alert alert-info">No hay servidores registrados aún.</div>
    @endforelse

</div>

{{-- Modal eliminar --}}
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">⚠️ Confirmar eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro que deseas eliminar a <strong id="nombreEliminar"></strong>?
                <br><small class="text-muted">Esta acción no se puede deshacer.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formEliminar" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEliminar(id, nombre) {
    document.getElementById('nombreEliminar').textContent = nombre;
    document.getElementById('formEliminar').action = '/servidores/' + id;
    new bootstrap.Modal(document.getElementById('modalEliminar')).show();
}
</script>
@endsection
