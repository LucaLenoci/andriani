<?php

namespace App\Http\Controllers;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class EventiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Evento::query();

            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'like', "%{$search}%")
                      ->orWhere('nomeEvento', 'like', "%{$search}%");
                });
            }

            $eventi = $query->orderBy('dataInizioEvento', 'desc')->paginate(20);

            return view('eventi.index', compact('eventi'));
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento degli eventi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Errore durante il caricamento degli eventi.');
        }
    }

    public function show($id)
    {
        try {
            $evento = Evento::findOrFail($id);
            return view('eventi.show', compact('evento'));
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento dell\'evento: ' . $e->getMessage());
            return redirect()->route('eventi.index')->with('error', 'Errore durante il caricamento dell\'evento.');
        }
    }

    public function create()
    {
        try {
            return view('eventi.create');
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento del form di creazione: ' . $e->getMessage());
            return redirect()->route('eventi.index')->with('error', 'Errore durante il caricamento del form di creazione.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nomeEvento' => 'required|max:255',
                'annoEvento' => 'required|integer',
                'dataInizioEvento' => 'required|date',
                'dataFineEvento' => 'required|date|after_or_equal:dataInizioEvento',
                'richiestaPresenzaPromoter' => 'nullable|boolean',
                'previstaAttivitaDiCaricamento' => 'nullable|boolean',
                'previstaAttivitaDiAllestimento' => 'nullable|boolean',
            ]);

            $request->merge([
                'idUtenteCreatoreEvento' => Auth::user()->id,
                'dataInserimentoEvento' => now('Europe/Rome'),
            ]);

            Evento::create($request->all());

            return redirect()->route('eventi.index')->with('success', 'Evento creato con successo.');
        } catch (Exception $e) {
            \Log::error('Errore durante la creazione dell\'evento: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Errore durante la creazione dell\'evento: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $evento = Evento::findOrFail($id);

            return view('eventi.edit', compact('evento'));
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento del form di modifica: ' . $e->getMessage());
            return redirect()->route('eventi.index')->with('error', 'Errore durante il caricamento del form di modifica.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $evento = Evento::findOrFail($id);

            $request->validate([
                'nomeEvento' => 'required|max:255',
                'annoEvento' => 'required|integer',
                'dataInizioEvento' => 'required|date',
                'dataFineEvento' => 'required|date|after_or_equal:dataInizioEvento',
                'richiestaPresenzaPromoter' => 'nullable|boolean',
                'previstaAttivitaDiCaricamento' => 'nullable|boolean',
                'previstaAttivitaDiAllestimento' => 'nullable|boolean',
            ]);

            $request->merge([
                'idUtenteModificatoreEvento' => Auth::user()->id,
                'dataModificaEvento' => now('Europe/Rome'),
            ]);

            $evento->update($request->all());

            return redirect()->route('eventi.index')->with('success', 'Evento aggiornato con successo.');
        } catch (Exception $e) {
            \Log::error('Errore durante l\'aggiornamento dell\'evento: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Errore durante l\'aggiornamento dell\'evento.');
        }
    }

    public function destroy($id)
    {
        try {
            $evento = Evento::findOrFail($id);
            $evento->delete();

            return redirect()->route('eventi.index')->with('success', 'Evento eliminato con successo.');
        } catch (Exception $e) {
            \Log::error('Errore durante l\'eliminazione dell\'evento: ' . $e->getMessage());
            return redirect()->route('eventi.index')->with('error', 'Errore durante l\'eliminazione dell\'evento.');
        }
    }
}
