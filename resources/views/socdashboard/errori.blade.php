@extends('layouts.app')
@section('title', 'Error Log Laravel')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light" style="text-align: center; justify-content: center;">
    <div class="collapse navbar-collapse" id="navbarNav" style="text-align: center; justify-content: center;">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.logs') }}">Interazioni Utente</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.statistiche') }}">Statistiche</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.errori') }}">Errori</a>
            </li>
        </ul>
    </div>
</nav>
<hr style="margin-bottom: 50px;">

<div class="container">
    <h2>Errori Laravel</h2>
    <form method="GET" style="text-align: center; justify-content: center;">
        <div class="row g-2 align-items-end">
            <div class="col-md-12">
                <input type="text" name="search" class="form-control" placeholder="Cerca per nome o messaggio" value="{{ request('search') }}">
            </div>
        </div>
        <div class="row g-2 align-items-end" style="text-align: center; justify-content: center;">
            <div class="col-md-2">
                <label>Data Inizio</label>
                <input type="date" name="data_inizio" class="form-control" value="{{ request('data_inizio') }}">
            </div>
            <div class="col-md-2">
                <label>Data Fine</label>
                <input type="date" name="data_fine" class="form-control" value="{{ request('data_fine') }}">
            </div>
        </div>
        <div class="row mb-2" style="text-align: center; justify-content: center;">
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100 mt-4">Filtra</button>
            </div>
            <div class="col-md-4">
                <a href="{{ route('dashboard.errori') }}" class="btn btn-secondary w-100 mt-4" style="background-color: red;">Resetta Filtri</a>
            </div>
        </div>
    </form>
    @if(empty($logs))
        <div class="alert alert-info">Nessun errore trovato nel log.</div>
    @else
        <div class="accordion" id="accordionErrors">
            @foreach($logs as $index => $log)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $index }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                            [{{ $log['timestamp'] }}] {{ $log['level'] }} - {{ $log['nome'] }}
                        </button>
                    </h2>
                    <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionErrors">
                        <div class="accordion-body">
                            <pre style="word-wrap: break-word; white-space: pre-wrap;">{{ $log['message'] }}</pre>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
