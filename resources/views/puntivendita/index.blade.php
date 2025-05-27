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

<h3 class="mb-4 text-start">Punti Vendita</h3>

<!-- FILTRI -->
<div class="mb-4">
    <form action="{{ route('punti-vendita.index') }}" method="GET" class="row g-2">
        <div class="col-12 col-sm-6 col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Cerca punto vendita..." value="{{ request('search') }}">
        </div>
        <div class="col-12 col-sm-6 col-md-2">
            <select name="regione" class="form-select">
                <option value="">Tutte le Regioni</option>
                @foreach ($regioni as $regione)
                    <option value="{{ $regione }}" {{ request('regione') == $regione ? 'selected' : '' }}>{{ $regione }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-sm-6 col-md-2">
            <select name="provincia" class="form-select">
                <option value="">Tutte le Province</option>
                @foreach ($province as $provincia)
                    <option value="{{ $provincia }}" {{ request('provincia') == $provincia ? 'selected' : '' }}>{{ $provincia }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-sm-6 col-md-2">
            <select name="citta" class="form-select">
                <option value="">Tutte le Città</option>
                @foreach ($citta as $city)
                    <option value="{{ $city }}" {{ request('citta') == $city ? 'selected' : '' }}>{{ $city }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Filtra</button>
            <a href="{{ route('punti-vendita.index') }}" class="btn btn-secondary w-100">Reset</a>
        </div>
    </form>
</div>

<!-- PAGINAZIONE SUPERIORE -->
<div class="d-flex justify-content-center mb-4">
    {{ $puntivendita->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
</div>

<!-- LISTA PUNTI VENDITA -->
@forelse($puntivendita as $pv)
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <div class="row text-center text-md-start align-items-center gy-3">
                <div class="col-12 col-md-2">
                    <h5 class="mb-2">ID: {{ $pv->id }}</h5>
                    <span class="badge bg-primary text-white px-3 py-2 fs-6 shadow-sm">
                        Codice: <strong>{{ $pv->codicePuntoVendita }}</strong>
                    </span>
                </div>
                <div class="col-12 col-md-4">
                    <h5 class="text-primary fw-bold">{{ $pv->insegnaPuntoVendita ?? 'N/A' }}</h5>
                    <div><i class="bi bi-building me-1"></i><strong>Ragione Sociale:</strong> {{ $pv->ragioneSocialePuntoVendita ?? 'N/A' }}</div>
                </div>
                <div class="col-12 col-md-4">
                    <div><i class="bi bi-geo-alt me-1"></i><strong>Indirizzo:</strong> {{ $pv->indirizzoPuntoVendita }}, {{ $pv->capPuntoVendita }} {{ $pv->cittaPuntoVendita }} ({{ $pv->provinciaPuntoVendita }})</div>
                </div>
                <div class="col-12 col-md-2">
                    <i class="bi bi-map me-1"></i><strong>Regione:</strong> {{ $pv->regione->nomeRegione ?? 'N/A' }}
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info text-center">Nessun punto vendita trovato.</div>
@endforelse

<!-- PAGINAZIONE INFERIORE -->
<div class="d-flex justify-content-center mb-4">
    {{ $puntivendita->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
</div>

@endsection
