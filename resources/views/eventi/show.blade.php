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
                                <p class="card-text">{{ $formattedValue }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Sezione punti vendita associati --}}
            <hr>
            <h3 class="card-title">Punti Vendita Associati</h3>
            <div class="mb-5"></div>
            <br>
            @if($puntiVendita->isEmpty())
                <p>Nessun punto vendita associato a questo evento.</p>
            @else
                <div class="row">
                    @foreach($puntiVendita as $punto)
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <p>{{ $punto->ragioneSocialePuntoVendita ?? 'Nome non disponibile' }}</p>
                                    @if(!empty($punto->codicePuntoVendita))
                                        <span class="text-muted">Codice: {{ $punto->codicePuntoVendita }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Sezione materiali associati --}}
            <hr>
            <h3 class="card-title">Materiali Associati</h3>
            <div class="mb-5"></div>
            <br>
            @if($materiali->isEmpty())
                <p>Nessun materiale associato a questo evento.</p>
            @else
                <div class="row">
                    @foreach($materiali as $materiale)
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <p>{{ $materiale->nomeMateriale ?? 'Nome non disponibile' }}</p>
                                    @if(!empty($materiale->codiceIdentificativoMateriale))
                                        <span class="text-muted">Codice Identificativo: {{ $materiale->codiceIdentificativoMateriale }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Sezione RIENTRO DATI associati --}}

            <hr>
            <h3 class="card-title">Passaggi registrati per l'evento ID {{ $evento->id }}</h3>
            <div class="mb-5"></div>
            <br>
            @if (!empty($datiPassaggi))
                <div class="row">
                    @foreach ($datiPassaggi as $passaggiUtente)
                        @if(count($passaggiUtente) > 0)
                            @php
                                $utenteNome = $passaggiUtente[0]['nomeUtente'] ?? 'Utente sconosciuto';
                                $utenteId = $passaggiUtente[0]['idUtente'] ?? null;
                                // Raggruppa i passaggi per prodotto
                                $passaggiPerProdotto = [];
                                foreach ($passaggiUtente as $passaggio) {
                                    $prodotto = $passaggio['prodotto'] ?? 'Prodotto non specificato';
                                    $passaggiPerProdotto[$prodotto][] = $passaggio;
                                }
                            @endphp
                            <div class="col-md-12 mb-4">
                                <div class="card card-primary shadow-sm">
                                    <div class="card-header font-weight-bold">
                                        <i class="fas fa-user mr-2"></i>Utente: {{ $utenteNome }} (ID: {{ $utenteId }})
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach ($passaggiPerProdotto as $prodotto => $passaggi)
                                                <div class="col-md-4 mb-3">
                                                    <div class="card card-success shadow-sm">
                                                        <div class="card-header text-center font-weight-bold">
                                                            Prodotto: {{ $prodotto }}<br>
                                                            @if(count($passaggi) > 0)
                                                                Passaggio ID: {{ $passaggi[0]['idPassaggio'] }}
                                                            @endif
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