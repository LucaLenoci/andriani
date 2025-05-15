@extends('layouts.app')

@section('title', 'Dettaglio Adesione')

@section('content_header')
    <h1><i class="fas fa-file-alt mr-2"></i>Dettaglio Adesione #{{ $adesione->id }}</h1>
@stop

@section('content')
    <div class="card card-success shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Informazioni Dettagliate</h3>
            <div class="card-tools">
                <a href="{{ route('adesioni.index') }}" class="btn btn-sm btn-light" title="Torna alla lista" >
                    <i class="fas fa-arrow-left"></i> Indietro
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover">
                <tbody>
                    @foreach($adesione->getAttributes() as $field => $value)
                        @php
                            // Indica qui i campi che sono date o datetime da formattare
                            $dateFields = ['dataInizioAdesione', 'dataFineAdesione', 'dataInserimentoAdesione', 'dataModificaAdesione', 'dataInvioAdesione', 'dataApprovazioneAdesione'];

                            // Ottieni label
                            $label = $fieldNames[$field] ?? ucfirst(str_replace('_', ' ', $field));

                            // Formatta solo se il campo è una data e il valore non è nullo
                            if (in_array($field, $dateFields) && $value) {
                                try {
                                    $dt = \Illuminate\Support\Carbon::parse($value);
                                    $formattedValue = $dt->format('d/m/Y H:i');
                                } catch (\Exception $e) {
                                    $formattedValue = $value;
                                }
                            } else {
                                $formattedValue = $value;
                            }

                            $fieldNames = [
                                'id' => 'ID Adesione',
                                'idEvento' => 'ID Evento',
                                'idPuntoVendita' => 'ID Punto Vendita',
                                'dataInizioAdesione' => 'Data Inizio',
                                'dataFineAdesione' => 'Data Fine',
                                'autorizzazioneExtraBudget' => 'Autorizzazione Extra Budget',
                                'richiestaFattibilitaAgenzia' => 'Richiesta Fattibilità Agenzia',
                                'noteAdesione' => 'Note Adesione',
                                'responsabileCuraAllestimento' => 'L\'Allestimento è a cura',
                                'statoAdesione' => 'Stato Adesione',
                                'idUtenteCreatoreAdesione' => 'Creata da',
                                'dataInserimentoAdesione' => 'Data Inserimento',
                                'idUtenteModificatoreAdesione' => 'Modificata da',
                                'dataModificaAdesione' => 'Data Modifica',
                                'dataInvioAdesione' => 'Data Invio all\'Agenzia',
                                'idUtenteApprovatoreAdesione' => 'Approvata da',
                                'dataApprovazioneAdesione' => 'Data Approvazione',
                                'corriereAdesione' => 'Corriere'
                            ];
                            $label = $fieldNames[$field] ?? ucfirst(str_replace('_', ' ', $field));
                        @endphp
                        <tr>
                            <th style="width: 30%; text-transform: capitalize; background: #f4f6f9;">{{ $label }}</th>
                            <td>{{ $formattedValue }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer text-right" >
            <a href="{{ route('adesioni.index') }}" class="btn btn-success">
                <i class="fas fa-arrow-left"></i> Torna alla lista
            </a>
        </div>
    </div>
@stop