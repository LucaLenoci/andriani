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
