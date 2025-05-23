<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adesione;
use App\Models\Evento;

class DatiPerMop extends Controller
{
    public function adesioni()
    {
        $adesioni = Adesioni::all(); 
        return response()->json($adesioni);
    }

    public function eventi()
    {
        $eventi = Evento::all(); 
        return response()->json($eventi);
    }

    public function ultimaAdesione()
    {
        $adesione = Adesione::orderBy('created_at', 'desc')->first();
        return response()->json($adesione);
    }

    public function ultimoEvento($eventoId)
    {
        $adesione = Adesione::where('evento_id', $eventoId)->orderBy('created_at', 'desc')->first();
        return response()->json($adesione);
    }

}