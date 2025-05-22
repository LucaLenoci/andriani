@extends('layouts.app')

@section('title', 'Dettaglio Adesione')

@section('content_header')
    <h1><i class="fas fa-file-alt mr-2"></i>Dettaglio Adesione #{{ $adesione->id }}</h1>
@stop

@section('content')
<div class="container">

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



    {{-- SEZIONE 1: DATI PRINCIPALI --}}
    <div class="card card-success mb-4">
        <div class="card-header">
            <strong>Dati Principali</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('adesioni.edit', $adesione->id) }}" method="GET">
                {{-- Campo Evento --}}
                <div class="form-group">
                    <label for="idEvento">Evento</label>
                    <select name="idEvento" id="idEvento" class="form-control" required onchange="this.form.submit()">
                        @foreach($eventi as $evento)
                            <option value="{{ $evento->id }}" 
                                {{ (request('idEvento', $adesione->idEvento) == $evento->id) ? 'selected' : '' }}>
                                {{ $evento->nomeEvento ?? 'Evento #' . $evento->id }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            <form action="{{ route('adesioni.update', $adesione->id) }}" method="POST">
            @csrf
            @method('PUT')


            {{-- Campo Evento --}}
            <input type="hidden" name="idEvento" value="{{ request('idEvento') }}">

            {{-- Punto Vendita --}}
            <div class="form-group">
                <label for="idPuntoVendita">Punto Vendita</label>
                <select name="idPuntoVendita" id="idPuntoVendita" class="form-control" required>
                    <option value="">-- Seleziona Punto Vendita --</option>
                    @foreach($puntiVendita as $puntoVendita)
                        <option value="{{ $puntoVendita->id }}"
                            {{ old('idPuntoVendita', $adesione->idPuntoVendita) == $puntoVendita->id ? 'selected' : '' }}>
                            {{ 'CODICE [' . $puntoVendita->codicePuntoVendita . '] - ' . ($puntoVendita->ragioneSocialePuntoVendita ?? 'Punto Vendita #ID[' . $puntoVendita->id . ']') }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Data Inizio --}}
            <div class="form-group">
                <label for="dataInizioAdesione">Data Inizio Adesione</label>
                <input type="date" name="dataInizioAdesione" id="dataInizioAdesione" class="form-control"
                    value="{{ old('dataInizioAdesione', isset($adesione->dataInizioAdesione) ? \Carbon\Carbon::parse($adesione->dataInizioAdesione)->format('Y-m-d') : '') }}" required>
            </div>

            {{-- Data Fine --}}
            <div class="form-group">
                <label for="dataFineAdesione">Data Fine Adesione</label>
                <input type="date" name="dataFineAdesione" id="dataFineAdesione" class="form-control"
                    value="{{ old('dataFineAdesione', isset($adesione->dataFineAdesione) ? \Carbon\Carbon::parse($adesione->dataFineAdesione)->format('Y-m-d') : '') }}">
            </div>
        </div>
    </div>

    {{-- SEZIONE 2: AUTORIZZAZIONI E RESPONSABILITÀ --}}
    <div class="card card-success mb-4">
        <div class="card-header">
            <strong>Autorizzazioni & Responsabilità</strong>
        </div>
        <div class="card-body">
            {{-- Autorizzazione Extra Budget --}}
            <div class="form-group">
                <label for="autorizzazioneExtraBudget">Autorizzazione Extra Budget</label>
                <select name="autorizzazioneExtraBudget" id="autorizzazioneExtraBudget" class="form-control">
                    <option value="0" {{ old('autorizzazioneExtraBudget', $adesione->autorizzazioneExtraBudget) == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('autorizzazioneExtraBudget', $adesione->autorizzazioneExtraBudget) == 1 ? 'selected' : '' }}>Sì</option>
                </select>
            </div>

            {{-- Richiesta Fattibilità Agenzia --}}
            <div class="form-group">
                <label for="richiestaFattibilitaAgenzia">Richiesta Fattibilità Agenzia</label>
                <select name="richiestaFattibilitaAgenzia" id="richiestaFattibilitaAgenzia" class="form-control">
                    <option value="0" {{ old('richiestaFattibilitaAgenzia', $adesione->richiestaFattibilitaAgenzia) == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('richiestaFattibilitaAgenzia', $adesione->richiestaFattibilitaAgenzia) == 1 ? 'selected' : '' }}>Sì</option>
                </select>
            </div>

            {{-- Responsabile Cura Allestimento --}}
            <div class="form-group">
                <label for="responsabileCuraAllestimento">Responsabile Cura Allestimento</label>
                <select name="responsabileCuraAllestimento" id="responsabileCuraAllestimento" class="form-control">
                    <option value="agenzia" {{ old('responsabileCuraAllestimento', $adesione->responsabileCuraAllestimento) === "agenzia" ? 'selected' : '' }}>Agenzia</option>
                    <option value="punto vendita" {{ old('responsabileCuraAllestimento', $adesione->responsabileCuraAllestimento) === "punto vendita" ? 'selected' : '' }}>Punto Vendita</option>
                </select>
            </div>
        </div>
    </div>

    {{-- SEZIONE 3: NOTE --}}
    <div class="card card-success mb-4">
        <div class="card-header">
            <strong>Note</strong>
        </div>
        <div class="card-body">
            {{-- Note Adesione --}}
            <div class="form-group">
                <label for="noteAdesione">Note Adesione</label>
                <textarea name="noteAdesione" id="noteAdesione" class="form-control" rows="4">{{ old('noteAdesione', $adesione->noteAdesione) }}</textarea>
            </div>
        </div>
    </div>

    {{-- SEZIONE 4: MATERIALE --}}
    <div class="card card-success mb-4">
        <div class="card-header">
            <strong>Materiale Adesione</strong>
        </div>
        <div class="card-body">
            @if(isset($materiali) && count($materiali) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Materiale</th>
                                <th>Codice Identificativo</th>
                                <th>Quantità</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($materiali as $index => $materiale)
                                <tr>
                                    <td>{{ $materiale->nomeMateriale ?? 'Materiale #' . $materiale->idMateriale }}</td>
                                    <td>{{ $materiale->codiceIdentificativoMateriale ?? '-' }}</td>
                                    <td>
                                        <input 
                                            type="number" 
                                            name="materiali[{{ $materiale->idMateriale }}][quantitaRichiesta]" 
                                            value="{{ old('materiali.' . $materiale->idMateriale . '.quantitaRichiesta', $materiale->quantitaRichiesta) }}" 
                                            min="0" 
                                            max="9999"
                                            class="form-control" 
                                            style="width: 100px;"
                                        >
                                        <input type="hidden" name="materiali[{{ $materiale->idMateriale }}][idMateriale]" value="{{ $materiale->idMateriale }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>Nessun materiale associato all'evento selezionato.</p>
            @endif
        </div>
    </div>

    {{-- SEZIONE 4: GIORNATE --}} 
    @php
        $tipiGiornata = [];
        if (!empty($eventi)) {
                foreach ($eventi as $evento) {
                    if ($evento->richiestaPresenzaPromoter) {
                    $tipiGiornata[] = ['chiave' => 'promoter', 'label' => 'Giornate Promoter'];
                }
                if ($evento->previstaAttivitaDiCaricamento) {
                    $tipiGiornata[] = ['chiave' => 'caricamento', 'label' => 'Giornate Caricamento'];
                }
                if ($evento->previstaAttivitaDiAllestimento) {
                    $tipiGiornata[] = ['chiave' => 'allestimento', 'label' => 'Giornate Allestimento'];
                }
            }
        }
    @endphp

    @foreach($tipiGiornata as $tipo)
    @php
        // Carica le giornate dal controller se disponibili, altrimenti da old input
        $giornateFromController = $giornate[$tipo['chiave']] ?? [];
        //trasforma jsno in array
        $giornateFromController = json_decode($giornateFromController, true) ?: [];

        $oldGiornate = [];
        if(old('giornate_json_'.$tipo['chiave'])) {
            $oldGiornate = json_decode(old('giornate_json_'.$tipo['chiave']), true) ?: [];
        }
        $giornateToShow = count($oldGiornate) > 0 ? $oldGiornate : $giornateFromController;
    @endphp
    <div class="card card-success mb-4">
        <div class="card-header">
            <strong>Crea {{ $tipo['label'] }}</strong>
        </div>
        <div class="card-body">
            <div class="form-row d-flex align-items-end flex-wrap gap-3 justify-content-center">
                <div class="form-group col-md-2 mb-2 mb-md-0">
                    <label for="nuovaDataGiornata_{{ $tipo['chiave'] }}">Data</label>
                    <input type="date" id="nuovaDataGiornata_{{ $tipo['chiave'] }}" class="form-control" placeholder="Data">
                </div>
                <div class="form-group col-md-2 mb-2 mb-md-0">
                    <label for="nuovoOrarioInizio_{{ $tipo['chiave'] }}">Orario Inizio</label>
                    <input type="time" id="nuovoOrarioInizio_{{ $tipo['chiave'] }}" class="form-control" placeholder="Orario Inizio">
                </div>
                <div class="form-group col-md-2 mb-2 mb-md-0">
                    <label for="nuovoOrarioFine_{{ $tipo['chiave'] }}">Orario Fine</label>
                    <input type="time" id="nuovoOrarioFine_{{ $tipo['chiave'] }}" class="form-control" placeholder="Orario Fine">
                </div>
                <div class="form-group col-md-2 mb-2 mb-md-0">
                    <label for="nuoviMinutiTotali_{{ $tipo['chiave'] }}">Minuti Totali</label>
                    <input type="number" min="0" id="nuoviMinutiTotali_{{ $tipo['chiave'] }}" class="form-control" placeholder="Minuti Totali">
                </div>
                <div class="form-group col-md-2 mb-2 mb-md-0">
                    <label for="nuovoNumeroRisorse_{{ $tipo['chiave'] }}">N. Risorse Richieste</label>
                    <input type="number" min="1" id="nuovoNumeroRisorse_{{ $tipo['chiave'] }}" class="form-control" placeholder="Risorse">
                </div>
                <div class="form-group col-md-2 d-flex align-items-end mb-2 mb-md-0">
                    <button type="button" id="aggiungiGiornataBtn_{{ $tipo['chiave'] }}" class="btn btn-primary w-100">
                        <i class="fas fa-plus"></i> Aggiungi
                    </button>
                </div>
            </div>
            <div class="w-100 text-center">
                <small class="form-text text-muted">Inserisci una nuova giornata e aggiungila all'elenco.</small>
            </div>
        </div>
        <div class="card-header">
            <strong>{{ $tipo['label'] }} Inserite</strong>
        </div>
        <div class="card-body">
            <ul id="giornateInseriteList_{{ $tipo['chiave'] }}" class="list-group mb-3">
                {{-- Le giornate aggiunte dinamicamente verranno mostrate qui --}}
                @foreach($giornateToShow as $idx => $giornata)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            @if(!empty($giornata['data']))
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $giornata['data'])->format('d/m/Y') }}
                            @endif
                            @if(!empty($giornata['orarioInizio']) && !empty($giornata['orarioFine']))
                                ({{ \Carbon\Carbon::createFromFormat('H:i', $giornata['orarioInizio'])->format('H:i') }} - {{ \Carbon\Carbon::createFromFormat('H:i', $giornata['orarioFine'])->format('H:i') }})
                            @endif
                            @if(!empty($giornata['minutiTotali']))
                                - {{ $giornata['minutiTotali'] }} min
                            @endif
                            @if(!empty($giornata['numeroRisorseRichieste']))
                                - {{ $giornata['numeroRisorseRichieste'] }} risorse
                            @endif
                        </span>
                        <input type="hidden" name="giornate_{{ $tipo['chiave'] }}[{{ $idx }}][data]" value="{{ $giornata['dataGiornata'] }}">
                        <input type="hidden" name="giornate_{{ $tipo['chiave'] }}[{{ $idx }}][orarioInizio]" value="{{ $giornata['orarioInizioGiornata'] ?? '' }}">
                        <input type="hidden" name="giornate_{{ $tipo['chiave'] }}[{{ $idx }}][orarioFine]" value="{{ $giornata['orarioFineGiornata'] ?? '' }}">
                        <input type="hidden" name="giornate_{{ $tipo['chiave'] }}[{{ $idx }}][minutiTotali]" value="{{ $giornata['minutiTotaliGiornata'] ?? '' }}">
                        <input type="hidden" name="giornate_{{ $tipo['chiave'] }}[{{ $idx }}][numeroRisorseRichieste]" value="{{ $giornata['numeroRisorseRichieste'] ?? '' }}">
                        <input type="hidden" name="giornate_{{ $tipo['chiave'] }}[{{ $idx }}][esigenzaGiornata]" value="{{ $tipo['chiave'] }}">
                        <button type="button" class="btn btn-danger btn-sm rimuovi-giornata-btn">&times;</button>
                    </li>
                @endforeach
            </ul>
            {{-- Campo nascosto per mantenere le giornate in formato JSON --}}
            <input type="hidden" name="giornate_json_{{ $tipo['chiave'] }}" id="giornate_json_{{ $tipo['chiave'] }}" value="{{ old('giornate_json_'.$tipo['chiave'], count($giornateFromController) ? json_encode($giornateFromController) : '') }}">
        </div>
    </div>
    @endforeach

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @foreach($tipiGiornata as $tipo)
            (function() {
                const chiave = @json($tipo['chiave']);
                const aggiungiBtn = document.getElementById('aggiungiGiornataBtn_' + chiave);
                const dataInput = document.getElementById('nuovaDataGiornata_' + chiave);
                const orarioInizioInput = document.getElementById('nuovoOrarioInizio_' + chiave);
                const orarioFineInput = document.getElementById('nuovoOrarioFine_' + chiave);
                const minutiTotaliInput = document.getElementById('nuoviMinutiTotali_' + chiave);
                const numeroRisorseInput = document.getElementById('nuovoNumeroRisorse_' + chiave);
                const lista = document.getElementById('giornateInseriteList_' + chiave);
                const giornateJsonInput = document.getElementById('giornate_json_' + chiave);

                let giornate = [];
                // Carica giornate da old input se presenti, altrimenti dal controller
                @if(old('giornate_json_'.$tipo['chiave']))
                    giornate = JSON.parse(@json(old('giornate_json_'.$tipo['chiave'])));
                @elseif(isset($giornate[$tipo['chiave']]) && count(json_decode($giornate[$tipo['chiave']], true)))
                    giornate = @json(json_decode($giornate[$tipo['chiave']], true));
                @endif

                function aggiornaLista() {
                    lista.innerHTML = '';
                    giornate.forEach((g, idx) => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex justify-content-between align-items-center';
                        let testo = '';
                        if (g.dataGiornata) {
                            const [year, month, day] = g.dataGiornata.split('-');
                            testo = `${day}/${month}/${year}`;
                        }
                        if (g.orarioInizioGiornata && g.orarioFineGiornata) {
                            // Formatta orari in H:i
                            const formatTime = t => {
                                if (!t) return '';
                                const [h, m] = t.split(':');
                                return `${h.padStart(2, '0')}:${m.padStart(2, '0')}`;
                            };
                            testo += ` (${formatTime(g.orarioInizioGiornata)} - ${formatTime(g.orarioFineGiornata)})`;
                        }
                        if (g.minutiTotaliGiornata) {
                            testo += ` - ${g.minutiTotaliGiornata} min`;
                        }
                        if (g.numeroRisorseRichieste) {
                            testo += ` - ${g.numeroRisorseRichieste} risorse`;
                        }
                        li.innerHTML = `
                            <span>${testo}</span>
                            <input type="hidden" name="giornate_${chiave}[${idx}][data]" value="${g.dataGiornata || ''}">
                            <input type="hidden" name="giornate_${chiave}[${idx}][orarioInizio]" value="${g.orarioInizioGiornata || ''}">
                            <input type="hidden" name="giornate_${chiave}[${idx}][orarioFine]" value="${g.orarioFineGiornata || ''}">
                            <input type="hidden" name="giornate_${chiave}[${idx}][minutiTotali]" value="${g.minutiTotaliGiornata || ''}">
                            <input type="hidden" name="giornate_${chiave}[${idx}][numeroRisorseRichieste]" value="${g.numeroRisorseRichieste || ''}">
                            <input type="hidden" name="giornate_${chiave}[${idx}][esigenzaGiornata]" value="${chiave}">
                            <button type="button" class="btn btn-danger btn-sm rimuovi-giornata-btn">&times;</button>
                        `;
                        li.querySelector('.rimuovi-giornata-btn').addEventListener('click', function() {
                            giornate.splice(idx, 1);
                            aggiornaLista();
                        });
                        lista.appendChild(li);
                    });
                    giornateJsonInput.value = JSON.stringify(giornate);
                }

                aggiungiBtn.addEventListener('click', function () {
                    const dataGiornata = dataInput.value;
                    const orarioInizioGiornata = orarioInizioInput.value;
                    const orarioFineGiornata = orarioFineInput.value;
                    const minutiTotaliGiornata = minutiTotaliInput.value;
                    const numeroRisorseRichieste = numeroRisorseInput.value;

                    if (!dataGiornata) {
                        alert('Inserisci una data.');
                        return;
                    }
                    // Evita duplicati per data
                    if (giornate.some(g => g.dataGiornata === dataGiornata)) {
                        alert('Questa giornata è già stata inserita.');
                        return;
                    }

                    if (!orarioInizioGiornata || !orarioFineGiornata || !minutiTotaliGiornata || !numeroRisorseRichieste) {
                        alert('Compila tutti i campi.');
                        return;
                    }

                    const inizio = new Date(`1970-01-01T${orarioInizioGiornata}:00`);
                    const fine = new Date(`1970-01-01T${orarioFineGiornata}:00`);
                    if (inizio >= fine) {
                        alert('L\'orario di inizio deve essere prima dell\'orario di fine.');
                        return;
                    }

                    const inizioOre = parseInt(orarioInizioGiornata.split(':')[0]);
                    const inizioMinuti = parseInt(orarioInizioGiornata.split(':')[1]);
                    const fineOre = parseInt(orarioFineGiornata.split(':')[0]);
                    const fineMinuti = parseInt(orarioFineGiornata.split(':')[1]);
                    if (inizioOre < 0 || inizioOre > 23 || inizioMinuti < 0 || inizioMinuti > 59 ||
                        fineOre < 0 || fineOre > 23 || fineMinuti < 0 || fineMinuti > 59) {
                        alert('L\'orario deve essere compreso tra 00:00 e 23:59.');
                        return;
                    }

                    giornate.push({
                        dataGiornata,
                        orarioInizioGiornata,
                        orarioFineGiornata,
                        minutiTotaliGiornata,
                        numeroRisorseRichieste,
                        esigenzaGiornata: chiave
                    });
                    aggiornaLista();
                    dataInput.value = '';
                    orarioInizioInput.value = '';
                    orarioFineInput.value = '';
                    minutiTotaliInput.value = '';
                    numeroRisorseInput.value = '';
                });

                aggiornaLista();
            })();
            @endforeach
        });
    </script>
    @endpush

    {{-- PULSANTI --}}
    <div class="text-right mb-4">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Salva
        </button>
        <a href="{{ route('adesioni.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Annulla
        </a>
    </div>
</form>

</div>
@endsection