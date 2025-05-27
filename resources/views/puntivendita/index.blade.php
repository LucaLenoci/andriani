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
    <form action="{{ route('puntivendita.index') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2">
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
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="row gy-3 align-items-center">
                <div class="col-12 col-md-3 d-flex flex-column align-items-center justify-content-center">
                    <h4 class="mb-2">ID: {{ $pv->id }}</h4>
                    <span class="badge p-3 fs-6" style="min-width: 120px; text-align: center; background-color: #0d6efd; color: #fff;">
                        {{ $pv->codicePuntoVendita }}
                    </span>
                </div>
                <div class="col-12 col-md-7">
                    <h5 class="mb-2"><strong>Insegna:</strong> {{ $pv->insegnaPuntoVendita ?? 'N/A' }}</h5>
                    <p class="mb-1"><strong>Ragione Sociale:</strong> {{ $pv->ragioneSocialePuntoVendita ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Indirizzo:</strong> {{ $pv->indirizzoPuntoVendita }}, {{ $pv->capPuntoVendita }} {{ $pv->cittaPuntoVendita }} ({{ $pv->provinciaPuntoVendita }})</p>
                    <p class="mb-1"><strong>Regione:</strong> {{ $pv->regione->nome ?? 'N/A' }}</p>
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
