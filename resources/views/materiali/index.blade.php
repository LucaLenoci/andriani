@extends('layouts.app')

@section('title', 'Materiali')

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

<h3 class="mb-4 text-start">Materiali</h3>

<!-- Ricerca -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
    <form action="{{ route('materiali.index') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2">
        <input type="text" name="search" class="form-control me-sm-2 mb-2 mb-sm-0" placeholder="Cerca materiale..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Cerca</button>
    </form>
</div>

<!-- PAGINAZIONE SUPERIORE -->
<div class="d-flex justify-content-center mb-4">
    {{ $materiali->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
</div>

<!-- LISTA MATERIALI -->
@forelse($materiali as $materiale)
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <div class="row text-center text-md-start align-items-center gy-3">
                <div class="col-12 col-md-2 mb-2 mb-md-0">
                    <h5 class="mb-2">ID: {{ $materiale->id }}</h5>
                    <span class="badge bg-primary text-white px-3 py-2 fs-6 shadow-sm">
                        Codice: <strong>{{ $materiale->codiceIdentificativoMateriale }}</strong>
                    </span>
                </div>
                <div class="col-12 col-md-4 mb-2 mb-md-0">
                    <h5 class="text-primary fw-bold mb-0">{{ $materiale->nomeMateriale ?? 'N/A' }}</h5>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info text-center">Nessun materiale trovato.</div>
@endforelse

<!-- PAGINAZIONE INFERIORE -->
<div class="d-flex justify-content-center mb-4">
    {{ $materiali->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
</div>

@endsection
