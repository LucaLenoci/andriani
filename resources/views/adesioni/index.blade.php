@extends('layouts.app')

@section('title', 'Adesioni')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
    <h3>Adesioni</h3>
    <form action="{{ route('adesioni.index') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2">
        <input type="text" name="search" class="form-control me-sm-2 mb-2 mb-sm-0" placeholder="Cerca adesione..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Cerca</button>
    </form>
    <a href="{{ route('adesioni.create') }}" class="btn btn-primary mt-2 mt-md-0">Nuova Adesione</a>
</div>

@forelse($adesioni as $adesione)
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="row gy-3 align-items-center">
                <!-- Stato a sinistra -->
                <div class="col-12 col-md-3 d-flex flex-column align-items-center justify-content-center">
                    <h4 class="mb-2">ID: {{ $adesione->id }}</h4>
                    <span class="badge p-3 fs-6" style="min-width: 120px; text-align: center; background-color: 
                        {{ $adesione->statoAdesione === 'inviata' ? '#198754' : ($adesione->statoAdesione === 'annullata' ? '#dc3545' : '#6c757d') }};
                        color: #fff;">
                        {{ ucfirst($adesione->statoAdesione) }}
                    </span>
                </div>

                <!-- Info a destra -->
                <div class="col-12 col-md-7">
                    <h5 class="mb-2"><strong>Evento:</strong> {{ $adesione->evento->nomeEvento ?? 'Evento non trovato' }}</h5>
                    <p class="mb-1"><strong>Periodo:</strong> 
                        {{ $adesione->dataInizioAdesione?->format('d/m/Y') }} - {{ $adesione->dataFineAdesione?->format('d/m/Y') }}
                    </p>
                    <p class="mb-1"><strong>Creato da:</strong> 
                        {{ $adesione->utenteCreatore->name ?? 'N/A' }}
                    </p>
                </div>

                <div class="col-12 col-md-2 mt-2 d-flex flex-row flex-md-column align-items-end gap-2">
                    <div class="d-flex flex-row flex-md-column w-100 gap-2">
                        <a href="{{ route('adesioni.show', $adesione->id) }}" class="btn btn-success btn-sm w-100">Visualizza</a>
                        <a href="{{ route('adesioni.edit', $adesione->id) }}" class="btn btn-warning btn-sm w-100">Modifica</a>
                        <form action="{{ route('adesioni.destroy', $adesione->id) }}" method="POST" class="d-inline w-100" onsubmit="return confirm('Sei sicuro di voler eliminare questa adesione?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm w-100">Elimina</button>
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
