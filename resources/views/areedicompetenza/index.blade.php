@extends('layouts.app')

@section('content')
<h3 class="mb-4 text-start">Aree di Competenza</h3>

<div class="container">
    <div class="row">
        <!-- Area NORD -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h3>Area NORD</h3>
                </div>
                <div class="card-body">
                    <h5>Responsabile: {{ $nordResponsabile }}</h5>
                    <div class="row">
                        @foreach($nordRegioni as $regione)
                            <div class="col-md-6 mb-2">
                                <div class="card">
                                    <div class="card-body text-center">
                                        {{ $regione }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
                    <div class="row">
                        @foreach($sudRegioni as $regione)
                            <div class="col-md-6 mb-2">
                                <div class="card">
                                    <div class="card-body text-center">
                                        {{ $regione }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection