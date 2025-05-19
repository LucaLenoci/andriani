@extends('layouts.app')

@section('title', 'Eventi')

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
    <h3>Eventi</h3>
    <form action="{{ route('eventi.index') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2">
        <input type="text" name="search" class="form-control me-sm-2 mb-2 mb-sm-0" placeholder="Cerca evento..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Cerca</button>
    </form>
    <a href="{{ route('eventi.create') }}" class="btn btn-primary mt-2 mt-md-0">Nuovo Evento</a>
</div>

<div class="d-flex justify-content-center mb-4">
    <div>
        {{ $eventi->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
</div>

@forelse($eventi as $evento)
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="row gy-3 align-items-center">
                <!-- Stato a sinistra -->
                <div class="col-12 col-md-3 d-flex flex-column align-items-center justify-content-center">
                    <h4 class="mb-2">ID: {{ $evento->id }}</h4>
                    @if($evento->statoEvento === 'annullato')
                        <span class="badge p-3 fs-6" style="min-width: 120px; text-align: center; background-color: #dc3545; color: #fff;">
                            Annullato
                        </span>
                    @endif
                </div>

                <!-- Info a destra -->
                <div class="col-12 col-md-7">
                    <h5 class="mb-2"><strong>Nome Evento:</strong> {{ $evento->nomeEvento ?? 'Evento non trovato' }}</h5>
                    <p class="mb-1"><strong>Periodo:</strong> 
                        {{ $evento->dataInizioEvento?->format('d/m/Y') }} - {{ $evento->dataFineEvento?->format('d/m/Y') }}
                    </p>
                    <p class="mb-1"><strong>Creato da:</strong> 
                        {{ $evento->utenteCreatore->name ?? 'N/A' }}
                    </p>
                </div>

                <div class="col-12 col-md-2 mt-2 d-flex flex-row flex-md-column align-items-end gap-2">
                    <div class="d-flex flex-row flex-md-column w-100 gap-2">
                        <a href="{{ route('eventi.show', $evento->id) }}" class="btn btn-primary btn-sm w-100">Visualizza</a>
                        
                        @if($evento->statoEvento !== 'annullato')
                            <a href="{{ route('eventi.edit', $evento->id) }}" class="btn btn-primary btn-sm w-100">Modifica</a>
                        @endif

                        @if($evento->statoEvento !== 'attivo' && $evento->statoEvento !== 'annullato')
                            <form action="{{ route('eventi.destroy', $evento->id) }}" method="POST" class="d-inline w-100" onsubmit="return confirm('Sei sicuro di voler annullare questo evento?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm w-100">Annulla</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">Nessun evento trovato.</div>
@endforelse

<div class="d-flex justify-content-center mb-4">
    <div>
        {{ $eventi->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
</div>

@endsection
