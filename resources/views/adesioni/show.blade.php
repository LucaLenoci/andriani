@extends('layouts.app')

@section('title', 'Dettaglio Adesione')

@section('content_header')
    <h1><i class="fas fa-file-alt mr-2"></i>Dettaglio Adesione #{{ $adesione->id }}</h1>
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
            <h3 class="card-title">Informazioni Dettagliate</h3>
            <div class="card-tools">
                <a href="{{ route('adesioni.index') }}" class="btn btn-sm btn-light" title="Torna alla lista" >
                    <i class="fas fa-arrow-left"></i> Indietro
                </a>
            </div>
        </div>
        <br>
        <div class="card-body">
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
            <div class="row">
                @foreach($adesione->getAttributes() as $field => $value)
                    @php
                        $dateFields = ['dataInizioAdesione', 'dataFineAdesione'];
                        $dateTimeFields = ['dataInserimentoAdesione', 'dataModificaAdesione', 'dataApprovazioneAdesione'];
                        $eventoBooleanFields = ['richiestaPresenzaPromoter', 'previstaAttivitaDiCaricamento', 'previstaAttivitaDiAllestimento'];
                        $booleanFields = ['autorizzazioneExtraBudget', 'richiestaFattibilitaAgenzia'];

                        $fieldNames = [
                            'id' => 'ID Adesione',
                            'idEvento' => 'ID e Nome Evento',
                            'idPuntoVendita' => 'ID Punto Vendita',
                            'dataInizioAdesione' => 'Data Inizio',
                            'dataFineAdesione' => 'Data Fine',
                            'autorizzazioneExtraBudget' => 'Autorizzazione Extra Budget',
                            'richiestaFattibilitaAgenzia' => 'Richiesta Fattibilità Agenzia',
                            'noteAdesione' => 'Note Adesione',
                            'responsabileCuraAllestimento' => "L'Allestimento è a cura",
                            'statoAdesione' => 'Stato Adesione',
                            'idUtenteCreatoreAdesione' => 'Creata da',
                            'dataInserimentoAdesione' => 'Data Inserimento',
                            'idUtenteModificatoreAdesione' => 'Modificata da',
                            'dataModificaAdesione' => 'Data Modifica',
                            'dataInvioAdesione' => "Data Invio all'Agenzia",
                            'idUtenteApprovatoreAdesione' => 'Approvata da',
                            'dataApprovazioneAdesione' => 'Data Approvazione',
                        ];

                        $label = $fieldNames[$field] ?? ucfirst(str_replace('_', ' ', $field));

                        if (in_array($field, $dateFields) && $value) {
                            try {
                                $dt = \Illuminate\Support\Carbon::parse($value);
                                $formattedValue = $dt->format('d/m/Y');
                            } catch (\Exception $e) {
                                $formattedValue = $value;
                            }
                        } elseif (in_array($field, $dateTimeFields) && $value) {
                            try {
                                $dt = \Illuminate\Support\Carbon::parse($value);
                                $formattedValue = $dt->format('d/m/Y H:i');
                            } catch (\Exception $e) {
                                $formattedValue = $value;
                            }
                        } elseif (in_array($field, $booleanFields)) {
                            $formattedValue = $value ? 'Sì' : 'No';
                        } elseif ($field === 'idEvento' && $adesione->evento) {
                            $formattedValue = $value . ' - ' . $adesione->evento->nomeEvento;
                        }elseif ($field === 'idPuntoVendita' && $adesione->puntoVendita) {
                            $formattedValue = $adesione->puntoVendita->codicePuntoVendita . ' - ' . $adesione->puntoVendita->ragioneSocialePuntoVendita;
                        } elseif ($field === 'idUtenteCreatoreAdesione' && $adesione->utenteCreatore) {
                            $formattedValue = $adesione->utenteCreatore->name;
                        }else {
                            $formattedValue = $value;
                        }
                    @endphp

                    @if ($label !== 'Note Adesione')
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $label }}</h6>
                                    <p class="card-text">{{ $formattedValue }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2 text-muted">{{ $label }}</h6>
                                        <p class="card-text">{{ $formattedValue }}</p>
                                    </div>
                                </div>
                            </div>
                    @endif
                    
                @endforeach
            </div>
            <div class="row">
                <div class="col-12">
                    <h4 class="mt-4 mb-3"><i class="fas fa-boxes mr-2"></i> Materiali Associati</h4>
                    <div class="row">
                        @if($materiali->isNotEmpty())
                            @foreach($materiali as $materiale)
                                <div class="col-md-4 mb-3">
                                    <div class="card border-success shadow h-100">
                                        <div class="card-header bg-secondary text-white py-2">
                                            <strong>{{ $materiale->nomeMateriale ?? '-' }}</strong>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-2">
                                                <span class="text-muted"><i class="fas fa-barcode mr-1"></i> Codice Identificativo:</span>
                                                <span class="font-weight-bold">{{ $materiale->codiceIdentificativoMateriale ?? '-' }}</span>
                                            </p>
                                            <p class="mb-0">
                                                <span class="text-muted"><i class="fas fa-cubes mr-1"></i> Quantità Richiesta:</span>
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
        </div>
        <div class="card-footer text-right" >
            <a href="{{ route('adesioni.index') }}" class="btn btn-success">
                <i class="fas fa-arrow-left"></i> Torna alla lista
            </a>
        </div>
    </div>
@stop