@extends('layouts.app')

@section('title', 'Punti Vendita')

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
    <h3>Punti Vendita</h3>
    <form action="{{ route('punti-vendita.index') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2">
        <input type="text" name="search" class="form-control me-sm-2 mb-2 mb-sm-0" placeholder="Cerca punto vendita..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Cerca</button>
    </form>
</div>

<div class="d-flex justify-content-center mb-4">
    <div>
        {{ $puntivendita->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
</div>

@forelse($puntivendita as $pv)
    <div class="card mb-4 shadow border-0">
        <div class="card-body py-4 px-3">
            <div class="row align-items-center">
                <div class="col-12 col-md-3 text-center mb-3 mb-md-0">
                    <h4 class="mb-2">ID: {{ $pv->id }}</h4>
                    <span class="badge bg-info text-dark mt-3 px-3 py-2 fs-6 shadow-sm">
                        Codice: <span class="fw-bold">{{ $pv->codicePuntoVendita }}</span>
                    </span>
                </div>
                <div class="col-12 col-md-6">
                    <h5 class="mb-2 text-primary fw-bold">
                        {{ $pv->insegnaPuntoVendita ?? 'N/A' }}
                    </h5>
                    <div class="mb-1">
                        <i class="bi bi-building me-1"></i>
                        <strong>Ragione Sociale:</strong> {{ $pv->ragioneSocialePuntoVendita ?? 'N/A' }}
                    </div>
                    <div class="mb-1">
                        <i class="bi bi-geo-alt me-1"></i>
                        <strong>Indirizzo:</strong>
                        {{ $pv->indirizzoPuntoVendita }},
                        {{ $pv->capPuntoVendita }}
                        {{ $pv->cittaPuntoVendita }}
                        ({{ $pv->provinciaPuntoVendita }})
                    </div>
                    <div>
                        <i class="bi bi-map me-1"></i>
                        <strong>Regione:</strong> {{ $pv->regione->nomeRegione ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">Nessun punto vendita trovato.</div>
@endforelse

<div class="d-flex justify-content-center mb-4">
    <div>
        {{ $puntivendita->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
</div>

@endsection
