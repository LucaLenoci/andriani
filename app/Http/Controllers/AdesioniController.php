<?php

namespace App\Http\Controllers;
use App\Models\Adesione;

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
}   