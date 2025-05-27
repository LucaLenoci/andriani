@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Rientro Dati Evento : {{ $evento->nomeEvento }} [ ID {{ $evento->id }} ]</h3>

    {{-- Filtri --}}
    <form method="GET" class="mb-4" id="filtri-passaggi-form">
        <div class="row">
            <div class="col-md-4">
                <label>Filtra per utente:</label>
                <select name="filtro_utente" class="form-control" onchange="this.form.submit()">
                    <option value="">Tutti</option>
                    @foreach($utentiUnici as $id => $nome)
                        <option value="{{ $id }}" {{ $filtroUtente == $id ? 'selected' : '' }}>{{ $nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Filtra per prodotto:</label>
                <select name="filtro_prodotto" class="form-control" onchange="this.form.submit()">
                    <option value="">Tutti</option>
                    @foreach(array_keys($prodottiUnici) as $prodotto)
                        <option value="{{ $prodotto }}" {{ $filtroProdotto == $prodotto ? 'selected' : '' }}>{{ $prodotto }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Filtra per punto vendita:</label>
                <select name="filtro_pdv" class="form-control" onchange="this.form.submit()">
                    <option value="">Tutti</option>
                    @foreach($pdvUnici as $codice => $label)
                        <option value="{{ $codice }}" {{ $filtroPdv == $codice ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <div class="mt-4 d-flex justify-content-center">
      {{ $gruppiPaginati->withQueryString()->links('pagination::bootstrap-4') }}
    </div>

    {{-- Passaggi --}}
    @if ($gruppiPaginati->count())
        @foreach ($gruppiPaginati as $gruppo)
            <div class="card mb-4 shadow">
                <div class="card-header text-white" style="background:#255459">
                    <strong><i class="fas fa-user mr-2"></i>{{ $gruppo['utenteNome'] }}</strong><br>
                    @if($gruppo['idPassaggio'])
                        ID Passaggio: {{ $gruppo['idPassaggio'] }}<br>
                        Punto Vendita: {{ $gruppo['pdv']['Codice'] ?? '' }} - {{ $gruppo['pdv']['RagioneSociale'] ?? '' }}
                    @endif
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($gruppo['prodotti'] as $prodotto => $passaggi)
                            <div class="col-md-4 mb-3">
                                <div class="card card-success shadow">
                                    <div class="card-header text-center font-weight-bold">
                                        Prodotto: {{ $prodotto }}
                                    </div>
                                    <div class="card-body">
                                        @foreach($passaggi as $p)
                                            <div class="mb-2 border-bottom pb-2">
                                                <strong>{{ $p['rd']['nome'] ?? 'Campo' }}:</strong> {{ $p['valore'] }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-warning">Nessun dato trovato per questo evento.</div>
    @endif

    <div class="mt-4 d-flex justify-content-center">
      {{ $gruppiPaginati->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
