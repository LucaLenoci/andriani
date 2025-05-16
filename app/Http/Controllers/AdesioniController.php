<?php

namespace App\Http\Controllers;
use App\Models\Adesione;
use App\Models\Evento;

class AdesioniController extends Controller
{
    public function index()
    {
        $adesioni = Adesione::all(); 
        return view('adesioni.index', compact('adesioni'));
    }

    public function show($id)
    {
        // Recupera l'adesione tramite id
        $adesione = Adesione::findOrFail($id);
        
        // Passa i dati alla view show (crea anche questa view!)
        return view('adesioni.mostraAdesione', compact('adesione'));
    }

    public function create()
    {
        $eventi = Evento::all(); // importa il modello Evento in cima con: use App\Models\Evento;

        // Logica per mostrare il form di creazione adesione
        return view('adesioni.create', compact('eventi'));
    }
}   