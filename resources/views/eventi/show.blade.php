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

@php
    // Icone associate a ciascun campo
    $eventoIcons = [
        'id' => 'fas fa-hashtag',
        'nomeEvento' => 'fas fa-tag',
        'annoEvento' => 'fas fa-calendar-alt',
        'dataInizioEvento' => 'fas fa-calendar-day',
        'dataFineEvento' => 'fas fa-calendar-day',
        'richiestaPresenzaPromoter' => 'fas fa-user-check',
        'previstaAttivitaDiCaricamento' => 'fas fa-box-open',
        'previstaAttivitaDiAllestimento' => 'fas fa-tools',
        'idUtenteCreatoreEvento' => 'fas fa-user',
        'dataInserimentoEvento' => 'fas fa-clock',
        'idUtenteModificatoreEvento' => 'fas fa-user-edit',
        'dataModificaEvento' => 'fas fa-edit',
    ];
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

                    $iconClass = $eventoIcons[$field] ?? 'fas fa-info-circle'; // icona di default se non definita
                @endphp
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">
                                <i class="{{ $iconClass }} mr-2"></i> {{ $label }}
                            </h6>
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
			<h5 class="mt-4 mb-3"><i class="fas fa-store mr-2"></i> Punti Vendita Associati</h5>
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
                                <div class="card-header bg-success text-white">
									<div>
										<strong>{{ $punto->insegnaPuntoVendita ?? 'Insegna non disponibile' }}</strong>
									</div>
									<div>
										{{ $punto->ragioneSocialePuntoVendita ?? 'Ragione Sociale non disponibile' }}
									</div>
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

										@if(!empty($punto->indirizzoPuntoVendita))
											<p class="mb-0">
												<i class="fas fa-road mr-1 text-secondary"></i>
												{{ $punto->indirizzoPuntoVendita }}
											</p>
										@endif

										@if(!empty($punto->capPuntoVendita) || !empty($punto->cittaPuntoVendita) || !empty($punto->provinciaPuntoVendita))
											@if(!empty($punto->capPuntoVendita))
												<p class="mb-0">
													<i class="fas fa-mail-bulk mr-1 text-secondary"></i>
													{{ $punto->capPuntoVendita }}
												</p>
											@endif
											@if(!empty($punto->cittaPuntoVendita) || !empty($punto->provinciaPuntoVendita))
												<p class="mb-0">
													@if(!empty($punto->cittaPuntoVendita))
														<i class="fas fa-city mr-1 text-secondary"></i>
														{{ $punto->cittaPuntoVendita }}
													@endif
													@if(!empty($punto->provinciaPuntoVendita))
														({{ $punto->provinciaPuntoVendita }})
													@endif
												</p>
											@endif

										@endif

										@if(!empty($punto->idRegionePuntoVendita))
											@php
												$regione = $regioni->firstWhere('id', $punto->idRegionePuntoVendita);
											@endphp
											<p class="mb-0 text-muted">
												<i class="fas fa-flag mr-1 text-secondary"></i>
												{{ $regione ? $regione->nomeRegione : '-' }}
											</p>
										@endif

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
                    <h5 class="mt-4 mb-3"><i class="fas fa-boxes mr-2"></i> Materiali Associati</h5>
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
            
            @else
                <div class="alert alert-warning mt-4">
                    Nessun dato trovato per questo evento.
                </div>
            @endif

        </div>
    </div>
@stop