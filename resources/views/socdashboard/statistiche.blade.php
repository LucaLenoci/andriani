@extends('layouts.app')
@section('title', 'Statistiche Interazioni Utente')

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
    <h2>Statistiche Interazioni Utente</h2>
    <br>
    <div class="row">
        <div class="col-md-6">
            <canvas id="graficoRuolo"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="graficoGiorno"></canvas>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <canvas id="graficoPagine Visitate"></canvas>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <canvas id="graficoTopUtenti"></canvas>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <hr>
        </div>
    </div>

    <h2>Statistiche Errori</h2>
    <br>
    <div class="row mt-4">
        <div class="col-md-12">
            <canvas id="graficoErroriNome"></canvas>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <canvas id="graficoErroriData"></canvas>
        </div>
    </div>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dataRuolo = @json($stats['per_ruolo']);
    const dataMetodo = @json($stats['per_metodo']);
    const dataGiorno = @json($stats['per_giorno']);
    const dataTopUtenti = @json($stats['top_utenti']);
    const dataPagineVisitate = @json($stats['pagine_visitate']);
    const erroriPerNome = @json($statisticheErrori['per_nome']);
    const erroriPerData = @json($statisticheErrori['per_data']);

    const commonOptions = (title) => ({
        responsive: true,
        animation: {
            duration: 1000,
            easing: 'easeOutQuart'
        },
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    font: {
                        size: 12
                    }
                }
            },
            title: {
                display: true,
                text: title,
                font: {
                    size: 16
                }
            },
            tooltip: {
                mode: 'index',
                intersect: false,
            }
        },
        interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
        }
    });

    new Chart(document.getElementById('graficoRuolo'), {
        type: 'bar',
        data: {
            labels: Object.keys(dataRuolo),
            datasets: [{
                label: 'Interazioni per ruolo',
                data: Object.values(dataRuolo),
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545']
            }]
        },
        options: commonOptions('Interazioni per Ruolo')
    });

    new Chart(document.getElementById('graficoGiorno'), {
        type: 'line',
        data: {
            labels: Object.keys(dataGiorno),
            datasets: [{
                label: 'Interazioni per giorno',
                data: Object.values(dataGiorno),
                fill: false,
                borderColor: '#6f42c1',
                tension: 0.3
            }]
        },
        options: commonOptions('Interazioni per Giorno')
    });

    new Chart(document.getElementById('graficoTopUtenti'), {
        type: 'bar',
        data: {
            labels: Object.keys(dataTopUtenti),
            datasets: [{
                label: 'Top Utenti Attivi (ID)',
                data: Object.values(dataTopUtenti),
                backgroundColor: '#fd7e14'
            }]
        },
        options: commonOptions('Top 5 Utenti Attivi')
    });

    new Chart(document.getElementById('graficoPagine Visitate'), {
        type: 'bar',
        data: {
            labels: Object.keys(dataPagineVisitate),
            datasets: [{
                label: 'Pagine Visitate',
                data: Object.values(dataPagineVisitate),
                backgroundColor: '#28a745'
            }]
        },
        options: commonOptions('Pagine più Visitate')
    });

    new Chart(document.getElementById('graficoErroriNome'), {
        type: 'bar',
        data: {
            labels: Object.keys(erroriPerNome),
            datasets: [{
                label: 'Errori per tipo',
                data: Object.values(erroriPerNome),
                backgroundColor: '#dc3545'
            }]
        },
        options: commonOptions('Errori più Frequenti')
    });

    new Chart(document.getElementById('graficoErroriData'), {
        type: 'line',
        data: {
            labels: Object.keys(erroriPerData),
            datasets: [{
                label: 'Errori per data',
                data: Object.values(erroriPerData),
                borderColor: '#17a2b8',
                fill: false,
                tension: 0.3
            }]
        },
        options: commonOptions('Errori nel Tempo')
    });
</script>
@endsection
