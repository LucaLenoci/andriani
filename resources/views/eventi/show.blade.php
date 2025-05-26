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

@if(isset($evento))
    @php
        // Definizione campi evento e formattazioni
        $eventoFields = [
            'id' => 'ID Evento',
            'nomeEvento' => 'Nome Evento',
            'annoEvento' => 'Anno Evento',
            'dataInizioEvento' => 'Data Inizio',
            'dataFineEvento' => 'Data Fine',
            'richiestaPresenzaPromoter' => 'Richiesta Presenza Promoter',
            'previstaAttivitaDiCaricamento' => 'Attività di Caricamento',
            'previstaAttivitaDiAllestimento' => 'Attività di Allestimento',
            'idUtenteCreatoreEvento' => 'Creato da',
            'dataInserimentoEvento' => 'Data Inserimento',
            'idUtenteModificatoreEvento' => 'Modificato da',
            'dataModificaEvento' => 'Data Modifica',
        ];
        $eventoDateTimeFields = ['dataInserimentoEvento', 'dataModificaEvento'];
        $eventoDateFields = ['dataInizioEvento', 'dataFineEvento'];
        $eventoBooleanFields = ['richiestaPresenzaPromoter', 'previstaAttivitaDiCaricamento', 'previstaAttivitaDiAllestimento'];
        
    @endphp

    <div class="card card-success shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Informazioni Dettagliate</h3>
            <div class="card-tools">
                <a href="{{ route('eventi.index') }}" class="btn btn-sm btn-light" title="Torna alla lista" >
                    <i class="fas fa-arrow-left"></i> Indietro
                </a>
            </div>
        </div>
        <br>
        <div class="card-body">
            <div class="row">
                @foreach($eventoFields as $field => $label)
                    @php
                        $value = $evento->$field ?? '';
                        if (in_array($field, $eventoDateTimeFields) && $value) {
                            try {
                                $dt = \Illuminate\Support\Carbon::parse($value);
                                $formattedValue = $dt->format('d/m/Y H:i');
                            } catch (\Exception $e) {
                                $formattedValue = $value;
                            }
                        } elseif (in_array($field, $eventoDateFields) && $value) {
                            try {
                                $dt = \Illuminate\Support\Carbon::parse($value);
                                $formattedValue = $dt->format('d/m/Y');
                            } catch (\Exception $e) {
                                $formattedValue = $value;
                            }
                        } elseif (in_array($field, $eventoBooleanFields)) {
                            $formattedValue = $value ? 'Sì' : 'No';
                        } elseif ($field === 'idUtenteCreatoreEvento' && $evento->utenteCreatore) {
                            $formattedValue = $evento->utenteCreatore->name;
                        } else {
                            $formattedValue = $value;
                        }
                    @endphp
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">{{ $label }}</h6>
                                @if($formattedValue === null || $formattedValue === '')
                                    <p class="card-text text-muted">-</p>
                                @else
                                    <p class="card-text">{{ $formattedValue }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- Sezione punti vendita associati --}}
            <hr>
            <h3 class="card-title"><i class="fas fa-store mr-2"></i>Punti Vendita Associati</h3>
            <div class="mb-4"></div>
            @if($puntiVendita->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>Nessun punto vendita associato a questo evento.
                </div>
            @else
                <div class="row">
                    @foreach($puntiVendita as $punto)
                        <div class="col-md-4 mb-3">
                            <div class="card border-success shadow-sm h-100">
                                <div class="card-header bg-success text-white d-flex align-items-center">
                                    <i class="fas fa-store mr-2"></i>
                                    <span>
                                        {{ ' ' . $punto->ragioneSocialePuntoVendita ?? 'Nome non disponibile' }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <p class="mb-2">
                                        <i class="fas fa-barcode mr-1 text-secondary"></i>
                                        <span class="text-muted">Codice:</span>
                                        <span class="font-weight-bold">
                                            {{ $punto->codicePuntoVendita ?? '-' }}
                                        </span>
                                    </p>
                                    @if(
                                        !empty($punto->indirizzoPuntoVendita) ||
                                        !empty($punto->capPuntoVendita) ||
                                        !empty($punto->cittaPuntoVendita) ||
                                        !empty($punto->provinciaPuntoVendita) ||
                                        !empty($punto->idRegionePuntoVendita)
                                    )
                                        <p class="mb-0">
                                            <i class="fas fa-map-marker-alt mr-1 text-secondary"></i>
                                            <span class="text-muted">Indirizzo:</span>
                                            <span>
                                                @if(!empty($punto->indirizzoPuntoVendita))
                                                    <i class="fas fa-road mr-1 text-secondary"></i>
                                                    {{ $punto->indirizzoPuntoVendita }}
                                                @endif
                                                @if(!empty($punto->capPuntoVendita) || !empty($punto->cittaPuntoVendita) || !empty($punto->provinciaPuntoVendita))
                                                    <br>
                                                    @if(!empty($punto->capPuntoVendita))
                                                        <i class="fas fa-mail-bulk mr-1 text-secondary"></i>
                                                        {{ $punto->capPuntoVendita }}
                                                    @endif
                                                    @if(!empty($punto->cittaPuntoVendita))
                                                        <i class="fas fa-city mr-1 text-secondary"></i>
                                                        {{ $punto->cittaPuntoVendita }}
                                                    @endif
                                                    @if(!empty($punto->provinciaPuntoVendita))
                                                        <i class="fas fa-map mr-1 text-secondary"></i>
                                                        ({{ $punto->provinciaPuntoVendita }})
                                                    @endif
                                                @endif
                                                @if(!empty($punto->idRegionePuntoVendita))
                                                    @php
                                                        $regione = $regioni->firstWhere('id', $punto->idRegionePuntoVendita);
                                                    @endphp
                                                    <br>
                                                    <span class="text-muted">
                                                        <i class="fas fa-flag mr-1 text-secondary"></i>Regione:
                                                    </span>
                                                    {{ $regione ? $regione->nome : '-' }}
                                                @else
                                                    <br>
                                                    <span class="text-muted">
                                                        <i class="fas fa-flag mr-1 text-secondary"></i>Regione:
                                                    </span>
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Sezione materiali associati --}}
            <div class="row">
                <div class="col-12">
                    <h4 class="mt-4 mb-3"><i class="fas fa-boxes mr-2"></i> Materiali Associati</h4>
                    <div class="row">
                        @if($materiali->isNotEmpty())
                            @foreach($materiali as $materiale)
                                <div class="col-md-4 mb-3">
                                    <div class="card border-success shadow h-100">
                                        <div class="card-header bg-success text-white py-2">
                                            <strong>{{ $materiale->nomeMateriale ?? '-' }}</strong>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-2">
                                                <span class="text-muted"><i class="fas fa-barcode mr-1"></i> Codice Identificativo:</span>
                                                <span class="font-weight-bold">{{ $materiale->codiceIdentificativoMateriale ?? '-' }}</span>
                                            </p>
                                            <p class="mb-0">
                                                <span class="text-muted"><i class="fas fa-cubes mr-1"></i> Quantità:</span>
                                                <span class="font-weight-bold">{{ $materiale->quantitaRichiesta ?? '-' }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="alert alert-info m-3 mb-0">
                                    Nessun materiale associato a questa adesione.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sezione RIENTRO DATI associati con filtri di visualizzazione --}}
            <hr>
            <h3 class="card-title">Passaggi registrati per l'evento ID {{ $evento->id }}</h3>
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