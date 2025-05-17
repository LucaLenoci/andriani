@extends('layouts.app')

@section('title', 'Dettaglio Evento')

@section('content_header')
    <h1><i class="fas fa-calendar-alt mr-2"></i>Dettaglio Evento #{{ $evento->id }}</h1>
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

<form action="{{ route('eventi.update', $evento->id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- SEZIONE 1: DATI PRINCIPALI --}}
    <div class="card card-success mb-4">
        <div class="card-header">
            <strong>Dati Principali</strong>
        </div>
        <div class="card-body">

            {{-- Nome Evento --}}
            <div class="form-group">
                <label for="nomeEvento">Nome Evento</label>
                <input type="text" name="nomeEvento" id="nomeEvento" class="form-control"
                    value="{{ old('nomeEvento', $evento->nomeEvento) }}" required>
            </div>

            {{-- Anno Evento --}}
            <div class="form-group">
                <label for="annoEvento">Anno Evento</label>
                <input type="number" name="annoEvento" id="annoEvento" class="form-control"
                    value="{{ old('annoEvento', $evento->annoEvento) }}" required>
            </div>

            {{-- Data Inizio Evento --}}
            <div class="form-group">
                <label for="dataInizioEvento">Data Inizio</label>
                <input type="date" name="dataInizioEvento" id="dataInizioEvento" class="form-control"
                    value="{{ old('dataInizioEvento', isset($evento->dataInizioEvento) ? \Carbon\Carbon::parse($evento->dataInizioEvento)->format('Y-m-d') : '') }}" required>
            </div>

            {{-- Data Fine Evento --}}
            <div class="form-group">
                <label for="dataFineEvento">Data Fine</label>
                <input type="date" name="dataFineEvento" id="dataFineEvento" class="form-control"
                    value="{{ old('dataFineEvento', isset($evento->dataFineEvento) ? \Carbon\Carbon::parse($evento->dataFineEvento)->format('Y-m-d') : '') }}">
            </div>
        </div>
    </div>

    {{-- SEZIONE 2: DETTAGLI --}}
    <div class="card card-success mb-4">
        <div class="card-header">
            <strong>Dettagli Evento</strong>
        </div>
        <div class="card-body">
            {{-- Richiesta Presenza Promoter --}}
            <div class="form-group">
                <label for="richiestaPresenzaPromoter">Richiesta Presenza Promoter</label>
                <select name="richiestaPresenzaPromoter" id="richiestaPresenzaPromoter" class="form-control">
                    <option value="0" {{ old('richiestaPresenzaPromoter', $evento->richiestaPresenzaPromoter) == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('richiestaPresenzaPromoter', $evento->richiestaPresenzaPromoter) == 1 ? 'selected' : '' }}>Sì</option>
                </select>
            </div>

            {{-- Prevista Attività di Caricamento --}}
            <div class="form-group">
                <label for="previstaAttivitaDiCaricamento">Prevista Attività di Caricamento</label>
                <select name="previstaAttivitaDiCaricamento" id="previstaAttivitaDiCaricamento" class="form-control">
                    <option value="0" {{ old('previstaAttivitaDiCaricamento', $evento->previstaAttivitaDiCaricamento) == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('previstaAttivitaDiCaricamento', $evento->previstaAttivitaDiCaricamento) == 1 ? 'selected' : '' }}>Sì</option>
                </select>
            </div>

            {{-- Prevista Attività di Allestimento --}}
            <div class="form-group">
                <label for="previstaAttivitaDiAllestimento">Prevista Attività di Allestimento</label>
                <select name="previstaAttivitaDiAllestimento" id="previstaAttivitaDiAllestimento" class="form-control">
                    <option value="0" {{ old('previstaAttivitaDiAllestimento', $evento->previstaAttivitaDiAllestimento) == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('previstaAttivitaDiAllestimento', $evento->previstaAttivitaDiAllestimento) == 1 ? 'selected' : '' }}>Sì</option>
                </select>
            </div>
        </div>
    </div>

    {{-- PULSANTI --}}
    <div class="text-right mb-4">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Salva
        </button>
        <a href="{{ route('eventi.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Annulla
        </a>
    </div>
</form>

</div>
@endsection
