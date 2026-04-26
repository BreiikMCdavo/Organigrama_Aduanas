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

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

            {{-- Foto --}}
            @if($servidor->fotografia)
                <img src="{{ asset('storage/' . $servidor->fotografia) }}"
                     class="rounded-circle flex-shrink-0"
                     style="width:60px;height:60px;object-fit:cover;border:2px solid #1565c0;">
            @else
                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white flex-shrink-0"
                     style="width:60px;height:60px;font-size:1.5rem;">👤</div>
            @endif

            {{-- Info --}}
            <div class="flex-grow-1">
                <div class="fw-bold fs-6">{{ $servidor->nombre }} {{ $servidor->apellido_paterno }} {{ $servidor->apellido_materno }}</div>
                <div class="text-muted mt-1">
                    @if($servidor->tipo === 'item')
                        <span class="badge bg-primary me-1">ÍTEM</span>
                        N° {{ $servidor->numero_item }} &bull; {{ $servidor->cargo }} &bull; {{ $servidor->designacion }}
                    @else
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
                            style="width:60px;height:60px;font-size:1.5rem;flex-shrink:0;">👤</div>
                    @endif
                </div>
                <div class="text-muted mt-1" style="font-size:0.875rem;">
                    Ingreso Aduana: {{ $servidor->fecha_ingreso_aduana ? \Carbon\Carbon::parse($servidor->fecha_ingreso_aduana)->format('d/m/Y') : '—' }}
                    @if($servidor->unidad)
                        &bull; {{ $servidor->unidad }}{{ $servidor->sub_unidad ? ' / '.$servidor->sub_unidad : '' }}
                    @endif
                </div>
            </div>
        @empty
            <div class="alert alert-info">No hay servidores registrados aún.</div>
        @endforelse

            {{-- Acciones --}}
            <div class="d-flex gap-2 flex-shrink-0">
                <a href="{{ route('servidores.show', $servidor->id) }}" class="btn btn-outline-primary">Ver</a>
                <a href="{{ route('servidores.edit', $servidor->id) }}" class="btn btn-outline-warning">Editar</a>
                <button type="button" class="btn btn-outline-danger"
                        onclick="confirmarEliminar({{ $servidor->id }}, '{{ addslashes($servidor->nombre.' '.$servidor->apellido_paterno) }}')">
                    Eliminar
                </button>
            </div>

        </div>

</div>

{{-- UN SOLO modal compartido --}}
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
