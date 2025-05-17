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

<form action="{{ route('adesioni.update', $adesione->id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- SEZIONE 1: DATI PRINCIPALI --}}
    <div class="card card-success mb-4">
        <div class="card-header">
            <strong>Dati Principali</strong>
        </div>
        <div class="card-body">
            {{-- Evento --}}
            <div class="form-group">
                <label for="idEvento">Evento</label>
                <select name="idEvento" id="idEvento" class="form-control" required>
                    <option value="">-- Seleziona Evento --</option>
                    @foreach($eventi as $evento)
                        <option value="{{ $evento->id }}"
                            {{ (old('idEvento', $adesione->idEvento) == $evento->id) ? 'selected' : '' }}>
                            {{ $evento->nomeEvento ?? 'Evento #' . $evento->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Punto Vendita --}}
            <div class="form-group">
                <label for="idPuntoVendita">ID Punto Vendita</label>
                <input type="text" name="idPuntoVendita" id="idPuntoVendita" class="form-control"
                    value="{{ old('idPuntoVendita', $adesione->idPuntoVendita) }}" required>
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