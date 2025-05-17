@extends('layouts.app')

@section('title', 'Nuovo Evento')

@section('content_header')
    <h1>Nuovo Evento</h1>
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

        <form action="{{ route('eventi.store') }}" method="POST">
            @csrf

            <div class="card card-success mb-4">
                <div class="card-header">
                    <strong>Dati Evento</strong>
                </div>
                <div class="card-body">
                    {{-- Nome Evento --}}
                    <div class="form-group">
                        <label for="nomeEvento">Nome Evento</label>
                        <input type="text" name="nomeEvento" id="nomeEvento" class="form-control" value="{{ old('nomeEvento') }}" required>
                    </div>

                    {{-- Anno Evento --}}
                    <div class="form-group">
                        <label for="annoEvento">Anno Evento</label>
                        <input type="number" name="annoEvento" id="annoEvento" class="form-control" value="{{ old('annoEvento', date('Y')) }}" required>
                    </div>

                    {{-- Data Inizio Evento --}}
                    <div class="form-group">
                        <label for="dataInizioEvento">Data Inizio</label>
                        <input type="date" name="dataInizioEvento" id="dataInizioEvento" class="form-control" value="{{ old('dataInizioEvento') }}" required>
                    </div>

                    {{-- Data Fine Evento --}}
                    <div class="form-group">
                        <label for="dataFineEvento">Data Fine</label>
                        <input type="date" name="dataFineEvento" id="dataFineEvento" class="form-control" value="{{ old('dataFineEvento') }}">
                    </div>
                </div>
            </div>

            <div class="card card-success mb-4">
                <div class="card-header">
                    <strong>Attività Evento</strong>
                </div>
                <div class="card-body">
                    {{-- Richiesta Presenza Promoter --}}
                    <div class="form-group">
                        <label for="richiestaPresenzaPromoter">Richiesta Presenza Promoter</label>
                        <select name="richiestaPresenzaPromoter" id="richiestaPresenzaPromoter" class="form-control" required>
                            <option value="0" {{ old('richiestaPresenzaPromoter') == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('richiestaPresenzaPromoter') == '1' ? 'selected' : '' }}>Sì</option>
                        </select>
                    </div>

                    {{-- Prevista Attività di Caricamento --}}
                    <div class="form-group">
                        <label for="previstaAttivitaDiCaricamento">Prevista Attività di Caricamento</label>
                        <select name="previstaAttivitaDiCaricamento" id="previstaAttivitaDiCaricamento" class="form-control" required>
                            <option value="0" {{ old('previstaAttivitaDiCaricamento') == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('previstaAttivitaDiCaricamento') == '1' ? 'selected' : '' }}>Sì</option>
                        </select>
                    </div>

                    {{-- Prevista Attività di Allestimento --}}
                    <div class="form-group">
                        <label for="previstaAttivitaDiAllestimento">Prevista Attività di Allestimento</label>
                        <select name="previstaAttivitaDiAllestimento" id="previstaAttivitaDiAllestimento" class="form-control" required>
                            <option value="0" {{ old('previstaAttivitaDiAllestimento') == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('previstaAttivitaDiAllestimento') == '1' ? 'selected' : '' }}>Sì</option>
                        </select>
                    </div>
                </div>
            </div>

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
@stop
