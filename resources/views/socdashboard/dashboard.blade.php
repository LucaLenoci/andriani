@extends('layouts.app')
@section('title', 'Dashboard Interazioni Utente')
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
    <h2>Dashboard Interazioni Utente</h2>
    <form method="GET" class="mb-4">
        <div class="row mb-2">
            <div class="col-md-12">
                <label>Ricerca</label>
                <input type="text" name="search" class="form-control" placeholder="Cerca..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="row g-2 align-items-end" style="text-align: center; justify-content: center;">
            <div class="col-md-2">
                <label>Ruolo</label>
                <select name="filter_role" class="form-select">
                    <option value="">-- Tutti --</option>
                    <option value="ADMIN" {{ request('filter_role') == 'ADMIN' ? 'selected' : '' }}>ADMIN</option>
                    <option value="AGENZIA" {{ request('filter_role') == 'AGENZIA' ? 'selected' : '' }}>AGENZIA</option>
                    <option value="TRADE MARKETING" {{ request('filter_role') == 'TRADE MARKETING' ? 'selected' : '' }}>TRADE MARKETING</option>
                    <option value="APPROVATORI" {{ request('filter_role') == 'APPROVATORI' ? 'selected' : '' }}>APPROVATORI</option>
                    <option value="FUNZIONARIO DI VENDITA" {{ request('filter_role') == 'FUNZIONARIO DI VENDITA' ? 'selected' : '' }}>FUNZIONARIO DI VENDITA</option>
                    <option value="CUSTOMER TEAM" {{ request('filter_role') == 'CUSTOMER TEAM' ? 'selected' : '' }}>CUSTOMER TEAM</option>
                    <option value="HELP DESK" {{ request('filter_role') == 'HELP DESK' ? 'selected' : '' }}>HELP DESK</option>
                    <option value="DISTRICT MANAGER" {{ request('filter_role') == 'DISTRICT MANAGER' ? 'selected' : '' }}>DISTRICT MANAGER</option>
                    <option value="LOCAL KEY ACCOUNT" {{ request('filter_role') == 'LOCAL KEY ACCOUNT' ? 'selected' : '' }}>LOCAL KEY ACCOUNT</option>

                </select>
            </div>

            <div class="col-md-2">
                <label>Metodo</label>
                <select name="filter_method" class="form-select">
                    <option value="">-- Tutti --</option>
                    <option value="GET" {{ request('filter_method') == 'GET' ? 'selected' : '' }}>GET</option>
                    <option value="POST" {{ request('filter_method') == 'POST' ? 'selected' : '' }}>POST</option>
                </select>
            </div>
    
            <div class="col-md-2">
                <label>Data Inizio</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>

            <div class="col-md-2">
                <label>Data Fine</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
        </div>
        <div class="row mb-2" style="text-align: center; justify-content: center;">
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100 mt-4">Filtra</button>
            </div>
            <div class="col-md-4">
                <a href="{{ route('dashboard.logs') }}" class="btn btn-secondary w-100 mt-4" style="background-color: red;">Resetta Filtri</a>
            </div>
        </div>
    </form>


    <table class="table table-striped table-bordered" style="table-layout: fixed; width: 100%;">
        <thead>
            <tr>
                <th style="width: 10%;">ID Utente</th>
                <th style="width: 15%;">Nome</th>
                <th style="width: 20%;">Email</th>
                <th style="width: 10%;">Ruolo</th>
                <th style="width: 10%;">IP</th>
                <th style="width: 15%;">URL</th>
                <th style="width: 10%;">Metodo</th>
                <th style="width: 10%;">Azione</th>
                <th style="width: 20%;">Dettagli</th>
                <th style="width: 15%;">Data/Ora</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td style="word-wrap: break-word;">{{ $log['id'] }}</td>
                    <td style="word-wrap: break-word;">{{ $log['username'] }}</td>
                    <td style="word-wrap: break-word;">{{ $log['email'] }}</td>
                    <td style="word-wrap: break-word;">{{ $log['ruolo'] }}</td>
                    <td style="word-wrap: break-word;">{{ $log['ip'] }}</td>
                    <td style="word-wrap: break-word;">{{ $log['url'] }}</td>
                    <td style="word-wrap: break-word;">{{ $log['method'] }}</td>
                    <td style="word-wrap: break-word;">{{ $log['action'] }}</td>
                    <td style="word-wrap: break-word;">{{ $log['details'] }}</td>
                    <td style="word-wrap: break-word;">{{ $log['time'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection
