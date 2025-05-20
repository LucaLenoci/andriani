@extends('layouts.app')

@section('title', 'Adesioni')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
    <h3>Adesioni</h3>
    <form action="{{ route('adesioni.index') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2">
        <input type="text" name="search" class="form-control me-sm-2 mb-2 mb-sm-0" placeholder="Cerca adesione..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Cerca</button>
    </form>
    <a href="{{ route('adesioni.create') }}" class="btn btn-primary mt-2 mt-md-0">Nuova Adesione</a>
</div>

<div class="d-flex justify-content-center mb-4">
    <div>
        {{ $adesioni->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
    <form action="{{ route('adesioni.index') }}" method="GET" class="ms-3">
        <select name="statoAdesione" class="form-select" onchange="this.form.submit()">
            <option value="">Tutti gli stati</option>
            <option value="inviata" {{ request('statoAdesione') == 'inviata' ? 'selected' : '' }}>Inviata</option>
            <option value="annullata" {{ request('statoAdesione') == 'annullata' ? 'selected' : '' }}>Annullata</option>
            <option value="bozza" {{ request('statoAdesione') == 'bozza' ? 'selected' : '' }}>Bozza</option>
        </select>
        @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif
    </form>
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
                        <a href="{{ route('adesioni.show', $adesione->id) }}" class="btn btn-primary btn-sm w-100">Visualizza</a>
                        
                        @if($adesione->statoAdesione !== 'annullata')
                            <a href="{{ route('adesioni.edit', $adesione->id) }}" class="btn btn-primary btn-sm w-100">Modifica</a>
                        @endif

                        @if($adesione->statoAdesione !== 'inviata' && $adesione->statoAdesione !== 'annullata')
                            <form action="{{ route('adesioni.destroy', $adesione->id) }}" method="POST" class="d-inline w-100" onsubmit="return confirm('Sei sicuro di voler eliminare questa adesione?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm w-100">Elimina</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">Nessuna adesione trovata.</div>
@endforelse

<div class="d-flex justify-content-center mb-4">
    <div>
        {{ $adesioni->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
</div>

@endsection


