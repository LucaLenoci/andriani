@extends('layouts.app')

@section('title', 'Dettaglio Evento')

@section('content_header')
    <h1><i class="fas fa-file-alt mr-2"></i>Dettaglio Evento #{{ $evento->id }}</h1>
@stop

@section('content')
{{-- Dettagli Evento --}}
@if(isset($evento))
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
        <div class="row">
            @php
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
            $eventoDateFields = ['dataInizioEvento', 'dataFineEvento', 'dataInserimentoEvento', 'dataModificaEvento'];
            $eventoBooleanFields = ['richiestaPresenzaPromoter', 'previstaAttivitaDiCaricamento', 'previstaAttivitaDiAllestimento'];
            @endphp
            @foreach($eventoFields as $field => $label)
                @php
                    $value = $evento->$field ?? '';
                    if (in_array($field, $eventoDateFields) && $value) {
                        try {
                            $dt = \Illuminate\Support\Carbon::parse($value);
                            $formattedValue = $dt->format('d/m/Y H:i');
                        } catch (\Exception $e) {
                            $formattedValue = $value;
                        }
                    } elseif (in_array($field, $eventoBooleanFields)) {
                        $formattedValue = $value ? 'Sì' : 'No';
                    }elseif ($field === 'idUtenteCreatoreEvento' && $evento->utenteCreatore) {
                        $formattedValue = $evento->utenteCreatore->name;
                    }else {
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
    </div>
@endif
@stop
