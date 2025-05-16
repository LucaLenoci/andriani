@extends('layouts.app')

@section('title', 'Adesioni')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Adesioni</h3>
    <a href="{{ route('adesioni.create') }}" class="btn btn-primary">Nuova Adesione</a>
</div>

@forelse($adesioni as $adesione)
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="row">
                <!-- Stato a sinistra -->
                <div class="col-md-3 d-flex flex-column align-items-center justify-content-center">
                    <h4 class="mb-2">ID: {{ $adesione->id }}</h4>
                    <span class="badge bg-{{ $adesione->statoAdesione === 'inviata' ? 'success' : ($adesione->statoAdesione === 'annullata' ? 'danger' : 'secondary') }} p-3 fs-6">
                        {{ ucfirst($adesione->statoAdesione) }}
                    </span>
                </div>

                <!-- Info a destra -->
                <div class="col-md-9">
                    <h5 class="mb-2">{{ $adesione->evento->nomeEvento ?? 'Evento non trovato' }}</h5>
                    <p class="mb-1"><strong>Periodo:</strong> 
                        {{ $adesione->dataInizioAdesione?->format('d/m/Y') }} - {{ $adesione->dataFineAdesione?->format('d/m/Y') }}
                    </p>
                    <p class="mb-1"><strong>Creato da:</strong> 
                        {{ $adesione->utenteCreatore->name ?? 'N/A' }}
                    </p>
                </div>

                <div class="col-12 mt-2 d-flex flex-column align-items-end gap-2">
                    <div class="d-flex flex-column w-auto">
                        <a href="{{ route('adesioni.show', $adesione->id) }}" class="btn btn-sm btn-success mb-1">Visualizza</a>
                        <a href="{{ route('adesioni.edit', $adesione->id) }}" class="btn btn-sm btn-warning mb-1">Modifica</a>
                        <form action="{{ route('adesioni.destroy', $adesione->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Sei sicuro di voler eliminare questa adesione?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Elimina</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">Nessuna adesione trovata.</div>
@endforelse
@endsection
