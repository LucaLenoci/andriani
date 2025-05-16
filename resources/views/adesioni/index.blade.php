@extends('layouts.app')

@section('title', 'Adesioni')

@section('content')
<div class="row">
    <div class="col-sm-6">
        <h3 class="mb-4">Adesioni</h3>
    </div>

    <div class="col-sm-6 text-end">
        <a href="{{ route('adesioni.create') }}" class="btn btn-primary mb-4">Nuova Adesione</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Stato</th>
                    <th>Dal</th>
                    <th>Al</th>
                    <th>Inserita da</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @forelse($adesioni as $adesione)
                    <tr>
                        <td>{{ $adesione->id }}</td>
                        <td>{{ $adesione->idEvento }}</td>
                        <td>{{ $adesione->statoAdesione }}</td>
                        <td>{{ $adesione->dataInizioAdesione ? $adesione->dataInizioAdesione->format('d/m/Y') : '' }}</td>
                        <td>{{ $adesione->dataFineAdesione ? $adesione->dataFineAdesione->format('d/m/Y') : '' }}</td>
                        <td>{{ $adesione->utenteCreatoreAdesione }}</td>

                        <td>
                            <a href="{{ route('adesioni.show', $adesione->id) }}" class="btn btn-sm btn-success">Visualizza</a>
                            <a href="{{ route('adesioni.edit', $adesione->id) }}" class="btn btn-sm btn-warning">Modifica</a>
                            <form action="{{ route('adesioni.destroy', $adesione->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Sei sicuro di voler eliminare questa adesione?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Elimina</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">Nessuna adesione trovata.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
