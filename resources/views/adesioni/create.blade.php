@extends('layouts.app')

@section('title', 'Nuova Adesione')

@section('content_header')
    <h1>Nuova Adesione</h1>
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

        
            @csrf

            {{-- SEZIONE 1: DATI PRINCIPALI --}}
            <div class="card card-success mb-4">
                <div class="card-header">
                    <strong>Dati Principali</strong>
                </div>
                <div class="card-body">
                    <form action="{{ route('adesioni.create') }}" method="GET">
                        {{-- Campo Evento --}}
                        <div class="form-group">
                            <label for="idEvento">Evento</label>
                            <select name="idEvento" id="idEvento" class="form-control" required onchange="this.form.submit()">
                                <option value="">-- Seleziona Evento --</option>
                                @foreach($eventi as $evento)
                                    <option value="{{ $evento->id }}" {{ request('idEvento') == $evento->id ? 'selected' : '' }}>
                                        {{ $evento->nomeEvento ?? 'Evento #' . $evento->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    <form action="{{ route('adesioni.store') }}" method="POST">
                    @csrf

                    {{-- Campo Evento --}}
                    <input type="hidden" name="idEvento" value="{{ request('idEvento') }}">

                    {{-- Punto Vendita --}}
                    <div class="form-group">
                        <label for="idPuntoVendita">Punto Vendita</label>
                        <select name="idPuntoVendita" id="idPuntoVendita" class="form-control" required>
                            <option value="">-- Seleziona Punto Vendita --</option>
                            @foreach($puntiVendita as $puntoVendita)
                                <option value="{{ $puntoVendita->id }}" {{ old('idPuntoVendita') == $puntoVendita->id ? 'selected' : '' }}>
                                    {{ 'CODICE [' . $puntoVendita->codicePuntoVendita . '] - ' . $puntoVendita->ragioneSocialePuntoVendita ?? 'Punto Vendita #ID[' . $puntoVendita->id . ']' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Data Inizio --}}
                    <div class="form-group">
                        <label for="dataInizioAdesione">Data Inizio Adesione</label>
                        <input type="date" name="dataInizioAdesione" id="dataInizioAdesione" class="form-control" value="{{ old('dataInizioAdesione') }}" required>
                    </div>

                    {{-- Data Fine --}}
                    <div class="form-group">
                        <label for="dataFineAdesione">Data Fine Adesione</label>
                        <input type="date" name="dataFineAdesione" id="dataFineAdesione" class="form-control" value="{{ old('dataFineAdesione') }}">
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
                            <option value="0" {{ old('autorizzazioneExtraBudget') == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('autorizzazioneExtraBudget') == 1 ? 'selected' : '' }}>Sì</option>
                        </select>
                    </div>

                    {{-- Richiesta Fattibilità Agenzia --}}
                    <div class="form-group">
                        <label for="richiestaFattibilitaAgenzia">Richiesta Fattibilità Agenzia</label>
                        <select name="richiestaFattibilitaAgenzia" id="richiestaFattibilitaAgenzia" class="form-control">
                            <option value="0" {{ old('richiestaFattibilitaAgenzia') == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('richiestaFattibilitaAgenzia') == 1 ? 'selected' : '' }}>Sì</option>
                        </select>
                    </div>

                    {{-- Responsabile Cura Allestimento --}}
                    <div class="form-group">
                        <label for="responsabileCuraAllestimento">Responsabile Cura Allestimento</label>
                        <select name="responsabileCuraAllestimento" id="responsabileCuraAllestimento" class="form-control">
                            <option value="agenzia" {{ old('responsabileCuraAllestimento') === "agenzia" ? 'selected' : '' }}>Agenzia</option>
                            <option value="punto vendita" {{ old('responsabileCuraAllestimento') === "punto vendita" ? 'selected' : '' }}>Punto Vendita</option>
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
                        <textarea name="noteAdesione" id="noteAdesione" class="form-control" rows="4">{{ old('noteAdesione') }}</textarea>
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
                                    <th>Quantità Disponibile</th>
                                    <th>Quantità per Adesione</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materiali as $materiale)
                                    <tr>
                                        <td>{{ $materiale->nomeMateriale ?? 'Materiale #' . $materiale->id }}</td>
                                        <td>{{ $materiale->codiceIdentificativoMateriale ?? '-' }}</td>
                                        <td>
                                            <input
                                                type="number"
                                                name="materiali[{{ $materiale->id }}][quantita]"
                                                class="form-control"
                                                min="0"
                                                max="9999"
                                                value="{{ old('materiali.' . $materiale->id . '.quantita', 0) }}"
                                            >
                                            <input type="hidden" name="materiali[{{ $materiale->id }}][id]" value="{{ $materiale->id }}">
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


            {{-- SEZIONE 5: GIORNATE --}}
            <div class="card card-success mb-4">
                <div class="card-header">
                    <strong>Giornate</strong>
                </div>
                <div class="card-body">
                    <div id="giornate-container">
                        @php
                            $giornateOld = old('giornate', isset($giornate) && count($giornate) > 0 ? $giornate->toArray() : []);
                        @endphp
                        @if(count($giornateOld) > 0)
                            @foreach($giornateOld as $idx => $giornata)
                                <div class="giornata-row row align-items-end mb-2">
                                    <div class="col-md-4">
                                        <label>Data</label>
                                        <input type="date" name="giornate[{{ $idx }}][data]" class="form-control" value="{{ $giornata['data'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Orario Inizio</label>
                                        <input type="time" name="giornate[{{ $idx }}][orarioInizio]" class="form-control" value="{{ $giornata['orarioInizio'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Orario Fine</label>
                                        <input type="time" name="giornate[{{ $idx }}][orarioFine]" class="form-control" value="{{ $giornata['orarioFine'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-remove-giornata" onclick="removeGiornataRow(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="giornata-row row align-items-end mb-2">
                                <div class="col-md-4">
                                    <label>Data</label>
                                    <input type="date" name="giornate[0][data]" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label>Orario Inizio</label>
                                    <input type="time" name="giornate[0][orarioInizio]" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label>Orario Fine</label>
                                    <input type="time" name="giornate[0][orarioFine]" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-remove-giornata" onclick="removeGiornataRow(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-primary mt-2" id="add-giornata-btn">
                        <i class="fas fa-plus"></i> Aggiungi Giornata
                    </button>
                </div>

                <script>
                    let giornataIndex = {{ count($giornateOld) > 0 ? count($giornateOld) : 1 }};
                    document.getElementById('add-giornata-btn').addEventListener('click', function() {
                        const container = document.getElementById('giornate-container');
                        const row = document.createElement('div');
                        row.className = 'giornata-row row align-items-end mb-2';
                        row.innerHTML = `
                            <div class="col-md-4">
                                <label>Data</label>
                                <input type="date" name="giornate[${giornataIndex}][data]" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label>Orario Inizio</label>
                                <input type="time" name="giornate[${giornataIndex}][orarioInizio]" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label>Orario Fine</label>
                                <input type="time" name="giornate[${giornataIndex}][orarioFine]" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-remove-giornata" onclick="removeGiornataRow(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `;
                        container.appendChild(row);
                        giornataIndex++;
                    });

                    function removeGiornataRow(btn) {
                        const row = btn.closest('.giornata-row');
                        row.parentNode.removeChild(row);
                    }
                </script>
            </div>

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
@stop
