@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Aree di Competenza e Responsabili</h1>
    <div class="row">
        <!-- Area NORD -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h3>Area NORD</h3>
                </div>
                <div class="card-body">
                    <h5>Responsabile: {{ $nordResponsabile }}</h5>
                    <ul>
                        @foreach($nordRegioni as $regione)
                            <li>{{ $regione }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <!-- Area SUD -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h3>Area SUD</h3>
                </div>
                <div class="card-body">
                    <h5>Responsabile: {{ $sudResponsabile }}</h5>
                    <ul>
                        @foreach($sudRegioni as $regione)
                            <li>{{ $regione }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection