<?php

namespace App\Http\Controllers;
use App\Models\Adesione;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdesioniController extends Controller
{
    public function index()
    {
        $adesioni = Adesione::orderBy('dataInizioAdesione', 'desc')->paginate(20); // 20 per pagina
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

    public function store(Request $request)
    {
        // Validazione dei dati
        $request->validate([
            'idEvento' => 'required|exists:eventi,id',
            'idPuntoVendita' => 'required|max:255',
            'dataInizioAdesione' => 'required|date',
            'dataFineAdesione' => 'required|date|after_or_equal:dataInizioAdesione',
            'autorizzazioneExtraBudget' => 'nullable|string|max:255',
            'richiestaFattibilitaAgenzia' => 'nullable|string|max:255',
            'responsabileCuraAllestimento' => 'nullable|string|max:255',
            'noteAdesione' => 'nullable|string|max:255'
        ]);

        $request->merge([
            'idUtenteCreatoreAdesione' => Auth::user()->id,
            'dataInserimentoAdesione' => now('Europe/Rome'),
            'statoAdesione' => 'bozza'
        ]);

        // Creazione dell'adesione
        Adesione::create($request->all());

        // Reindirizza alla lista delle adesioni
        return redirect()->route('adesioni.index')->with('success', 'Adesione creata con successo.');
    }
}   