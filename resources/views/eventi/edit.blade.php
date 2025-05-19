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

    {{-- SEZIONE 3: PUNTI VENDITA --}}
    <div class="card card-success mb-4">
        <div class="card-header">
            <strong>Punti Vendita</strong>
        </div>

        <div class="card-body">
            <div class="form-group">
                <label for="puntiVenditaSearch">Cerca Punto Vendita</label>
                <input type="text" id="puntiVenditaSearch" class="form-control mb-2" placeholder="Cerca per nome o codice...">

                <div id="puntiVenditaList" style="max-height: 250px; overflow-y: auto; border: 1px solid #ced4da; border-radius: 4px; padding: 10px;">
                    @foreach($puntiVendita as $pv)
                        <div class="form-check">
                            @php
                                $selectedIds = old('puntiVendita', $puntiVenditaSelezionati->pluck('id')->toArray() ?? []);
                            @endphp

                            <input
                                class="form-check-input pv-checkbox d-none"
                                type="checkbox"
                                name="puntiVendita[]"
                                value="{{ $pv->id }}"
                                id="pv_{{ $pv->id }}"
                                data-label="{{ $pv->ragioneSocialePuntoVendita }} ({{ $pv->codicePuntoVendita }})"
                                {{ in_array($pv->id, $selectedIds) ? 'checked' : '' }}
>
                            <label class="form-check-label" for="pv_{{ $pv->id }}">
                                {{ $pv->ragioneSocialePuntoVendita }} ({{ $pv->codicePuntoVendita }})
                            </label>
                        </div>
                    @endforeach
                </div>
                <small class="form-text text-muted">Cerca e seleziona i punti vendita desiderati.</small>
            </div>
        </div>

        <div class="card-body">
    <label>Punti Vendita Selezionati</label>
    <ul id="selectedPuntiVendita" class="list-group">
        @foreach ($puntiVenditaSelezionati as $pv)
            <li class="list-group-item d-flex justify-content-between align-items-center" data-value="{{ $pv->id }}">
                {{ $pv->ragioneSocialePuntoVendita }} ({{ $pv->codicePuntoVendita }})
                <input type="hidden" name="selectedPuntiVendita[]" value="{{ $pv->id }}">
                <button type="button" class="btn btn-danger btn-sm ml-2 remove-btn">Rimuovi</button>
            </li>
        @endforeach
    </ul>
</div>

    </div>

    <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const searchInput = document.getElementById('puntiVenditaSearch');
                    const puntiVenditaList = document.getElementById('puntiVenditaList');
                    const selectedList = document.getElementById('selectedPuntiVendita');

                    // Aggiunge un elemento alla lista selezionata senza aggiornarla tutta
                    function addSelectedListItem(cb) {
                        const label = cb.getAttribute('data-label');
                        const value = cb.value;

                        // Non aggiungere se già presente
                        for (const li of selectedList.children) {
                            if (li.dataset.value === value) {
                                return;
                            }
                        }

                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex justify-content-between align-items-center';
                        li.dataset.value = value;
                        li.textContent = label;

                        // Hidden input da inviare nel form
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'selectedPuntiVendita[]';
                        hiddenInput.value = value;

                        li.appendChild(hiddenInput);

                        // Bottone Rimuovi
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'btn btn-danger btn-sm ml-2';
                        removeBtn.textContent = 'Rimuovi';
                        removeBtn.addEventListener('click', function () {
                            li.remove();
                            cb.checked = false;
                        });

                        li.appendChild(removeBtn);
                        selectedList.appendChild(li);
                    }

                    // Inizializza la lista solo con i selezionati (una volta)
                    function initializeSelectedList() {
                        selectedList.innerHTML = '';
                        document.querySelectorAll('.pv-checkbox:checked').forEach(cb => {
                            addSelectedListItem(cb);
                        });
                    }

                    // Aggiorna la lista solo aggiungendo o rimuovendo il singolo elemento
                    puntiVenditaList.addEventListener('change', function (e) {
                        if (e.target.classList.contains('pv-checkbox')) {
                            if (e.target.checked) {
                                addSelectedListItem(e.target);
                            } 
                        }
                    });

                    // Search filter con chiamata AJAX
                    searchInput.addEventListener('input', function () {
                        const filter = searchInput.value.toLowerCase();

                        fetch(`/punti-vendita/search?term=${encodeURIComponent(filter)}`)
                            .then(response => response.json())
                            .then(data => {
                                // Salva gli ID già selezionati
                                const selectedIds = Array.from(document.querySelectorAll('.pv-checkbox:checked')).map(cb => cb.value);

                                // Svuota la lista
                                puntiVenditaList.innerHTML = '';

                                data.forEach(pv => {
                                    const wrapper = document.createElement('div');
                                    wrapper.className = 'form-check pv-item';

                                    const id = `pv_${pv.id}`;
                                    const labelText = `${pv.ragioneSocialePuntoVendita} (${pv.codicePuntoVendita})`;

                                    wrapper.innerHTML = `
                                        <input class="form-check-input pv-checkbox d-none" type="checkbox"
                                            name="puntiVendita[]" value="${pv.id}" id="${id}"
                                            data-label="${labelText}"
                                            ${selectedIds.includes(pv.id.toString()) ? 'checked' : ''}>
                                        <label class="form-check-label" for="${id}">${labelText}</label>
                                    `;

                                    puntiVenditaList.appendChild(wrapper);
                                });

                            });
                    });

                    selectedList.addEventListener('click', function (e) {
                        if (e.target.classList.contains('remove-btn')) {
                            const li = e.target.closest('li');
                            const value = li.dataset.value;

                            // Deseleziona la checkbox corrispondente nella lista principale
                            const checkbox = document.querySelector(`.pv-checkbox[value="${value}"]`);
                            if (checkbox) {
                                checkbox.checked = false;
                            }

                            li.remove();
                        }
                    });

                });
            </script>


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