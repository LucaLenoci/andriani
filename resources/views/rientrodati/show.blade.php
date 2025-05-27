@extends('layouts.app')

@section('title', 'Dettaglio Evento')

@section('content_header')
    <h1><i class="fas fa-file-alt mr-2"></i>Dettaglio Evento #{{ $evento->id }}</h1>
@stop

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

<div class="card card-success shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Rientro Dati - Evento ID {{ $evento->nomeEvento }}</h3>
        <div class="card-tools">
            <a href="{{ route('eventi.index') }}" class="btn btn-sm btn-light" title="Torna alla lista" >
                <i class="fas fa-arrow-left"></i> Indietro
            </a>
        </div>
    </div>
    <br>
    <div class="card-body">
        <div class="row">
            {{-- Sezione RIENTRO DATI associati con filtri di visualizzazione --}}
            <hr>
            <h3 class="card-title">Passaggi registrati per l'evento ID {{ $evento->nomeEvento }}</h3>
            <div class="mb-3"></div>
            <br><br>
            {{-- Filtri di visualizzazione --}}
            <form method="GET" class="mb-4" id="filtri-passaggi-form">
                <div class="row">
                    <div class="col-md-12 mb-2 d-flex justify-content-center">
                        <div class="row w-100 justify-content-center">
                            <div class="col-md-4 mb-2">
                                <label for="filtro_utente">Filtra per utente:</label>
                                <select name="filtro_utente" id="filtro_utente" class="form-control" onchange="document.getElementById('filtri-passaggi-form').submit();">
                                    <option value="">Tutti</option>
                                    @php
                                        $utentiUnici = [];
                                        foreach ($datiPassaggi as $passaggiUtente) {
                                            if (count($passaggiUtente) > 0) {
                                                $utenteId = $passaggiUtente[0]['idUtente'] ?? '';
                                                $utenteNome = $passaggiUtente[0]['nomeUtente'] ?? 'Utente sconosciuto';
                                                $utentiUnici[$utenteId] = $utenteNome;
                                            }
                                        }
                                    @endphp
                                    @foreach ($utentiUnici as $utenteId => $utenteNome)
                                        <option value="{{ $utenteId }}" {{ request('filtro_utente') == $utenteId ? 'selected' : '' }}>
                                            {{ $utenteNome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="filtro_prodotto">Filtra per prodotto:</label>
                                <select name="filtro_prodotto" id="filtro_prodotto" class="form-control" onchange="document.getElementById('filtri-passaggi-form').submit();">
                                    <option value="">Tutti</option>
                                    @php
                                        $prodottiUnici = [];
                                        foreach ($datiPassaggi as $passaggiUtente) {
                                            foreach ($passaggiUtente as $passaggio) {
                                                $prodottiUnici[$passaggio['prodotto'] ?? 'Prodotto non specificato'] = true;
                                            }
                                        }
                                    @endphp
                                    @foreach (array_keys($prodottiUnici) as $prodotto)
                                        <option value="{{ $prodotto }}" {{ request('filtro_prodotto') == $prodotto ? 'selected' : '' }}>
                                            {{ $prodotto }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="filtro_pdv">Filtra per punto vendita:</label>
                                <select name="filtro_pdv" id="filtro_pdv" class="form-control" onchange="document.getElementById('filtri-passaggi-form').submit();">
                                    <option value="">Tutti</option>
                                    @php
                                        $pdvUnici = [];
                                        foreach ($datiPassaggi as $passaggiUtente) {
                                            foreach ($passaggiUtente as $passaggio) {
                                                if (isset($passaggio['pdv']['Codice'])) {
                                                    $codice = $passaggio['pdv']['Codice'];
                                                    $ragione = $passaggio['pdv']['RagioneSociale'] ?? '';
                                                    $pdvUnici[$codice] = $codice . ($ragione ? ' - ' . $ragione : '');
                                                }
                                            }
                                        }
                                    @endphp
                                    @foreach ($pdvUnici as $codice => $label)
                                        <option value="{{ $codice }}" {{ request('filtro_pdv') == $codice ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="mb-3"></div>
            @php
                $filtroUtente = request('filtro_utente');
                $filtroProdotto = request('filtro_prodotto');
                $filtroPdv = request('filtro_pdv');
            @endphp

            @if (!empty($datiPassaggi))
                <div class="row">
                    @foreach ($datiPassaggi as $passaggiUtente)
                        @if(count($passaggiUtente) > 0)
                            @php
                                $utenteNome = $passaggiUtente[0]['nomeUtente'] ?? 'Utente sconosciuto';
                                $utenteId = $passaggiUtente[0]['idUtente'] ?? null;

                                // Applica filtro utente
                                if ($filtroUtente && $utenteId != $filtroUtente) {
                                    continue;
                                }

                                // Raggruppa i passaggi per prodotto
                                $passaggiPerProdotto = [];
                                foreach ($passaggiUtente as $passaggio) {
                                    $prodotto = $passaggio['prodotto'] ?? 'Prodotto non specificato';
                                    // Applica filtro prodotto
                                    if ($filtroProdotto && $prodotto != $filtroProdotto) {
                                        continue;
                                    }
                                    $passaggiPerProdotto[$prodotto][] = $passaggio;
                                }

                                // Applica filtro punto vendita
                                if ($filtroPdv) {
                                    $passaggiPerProdotto = array_filter($passaggiPerProdotto, function($passaggi) use ($filtroPdv) {
                                        foreach ($passaggi as $passaggio) {
                                            if (isset($passaggio['pdv']['Codice']) && $passaggio['pdv']['Codice'] == $filtroPdv) {
                                                return true;
                                            }
                                        }
                                        return false;
                                    });
                                }
                            @endphp
                            @if(count($passaggiPerProdotto) > 0)
                            <div class="col-md-12 mb-4">
                                <div class="card shadow-sm">
                                    <div class="card-header font-weight-bold" style="background-color:rgb(37, 84, 89); color:white;">
                                        <div>
                                            <i class="fas fa-user mr-2"></i> Utente: {{ $utenteNome }}
                                        </div>
                                        @if(count($passaggiUtente) > 0)
                                            <div>
                                                Passaggio ID: {{ $passaggiUtente[0]['idPassaggio'] }}
                                            </div>
                                            <div>
                                                Punto Vendita: {{ $passaggiUtente[0]['pdv']['Codice'] }} - {{ $passaggiUtente[0]['pdv']['RagioneSociale'] }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <div class="row justify-content-center">
                                            @foreach ($passaggiPerProdotto as $prodotto => $passaggi)
                                                <div class="col-md-4 mb-3">
                                                    <div class="card card-success shadow-sm">
                                                        <div class="card-header text-center font-weight-bold">
                                                            Prodotto: {{ $prodotto }}
                                                        </div>
                                                        <div class="card-body">
                                                            @foreach ($passaggi as $passaggio)
                                                                <div class="mb-2 border-bottom pb-2">
                                                                    <div><strong>{{ $passaggio['rd']['nome'] }}:</strong> {{ $passaggio['valore'] }}</div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endif
                    @endforeach
                </div>
            
            @else
                <div class="alert alert-warning mt-4">
                    Nessun dato trovato per questo evento.
                </div>
            @endif

        </div>
    </div>
@endif
@stop