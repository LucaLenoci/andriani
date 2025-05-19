<?php

namespace App\Http\Controllers;
use App\Models\Adesione;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class AdesioniController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Adesione::query();

            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'like', "%{$search}%")
                      ->orWhere('idEvento', 'like', "%{$search}%")
                      ->orWhereHas('evento', function ($subQuery) use ($search) {
                            $subQuery->where('nomeEvento', 'like', "%{$search}%");
                        })
                      ->orWhere('idPuntoVendita', 'like', "%{$search}%")
                      ->orWhere('idUtenteCreatoreAdesione', 'like', "%{$search}%");
                });
            }

            $adesioni = $query->orderBy('dataInizioAdesione', 'desc')->paginate(20);

            return view('adesioni.index', compact('adesioni'));
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento delle adesioni: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Errore durante il caricamento delle adesioni.');
        }
    }

    public function show($id)
    {
        try {
            $adesione = Adesione::findOrFail($id);
            return view('adesioni.show', compact('adesione'));
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento dell\'adesione: ' . $e->getMessage());
            return redirect()->route('adesioni.index')->with('error', 'Errore durante il caricamento dell\'adesione.');
        }
    }

public function create(Request $request)
{
    try {
        $eventi = Evento::all();
        $puntiVendita = collect(); // Vuoto di default

        if ($request->has('idEvento') && !empty($request->idEvento)) {
            $eventoId = $request->idEvento;
            $puntiVendita = \DB::table('eventopuntivendita')
                ->join('puntivendita', 'eventopuntivendita.idPuntoVendita', '=', 'puntivendita.id')
                ->select('puntivendita.*')
                ->where('eventopuntivendita.idEvento', $eventoId)
                ->get();
        }

        return view('adesioni.create', compact('eventi', 'puntiVendita'));

    } catch (Exception $e) {
        \Log::error('Errore durante il caricamento del form di creazione: ' . $e->getMessage());
        return redirect()->route('adesioni.index')->with('error', 'Errore durante il caricamento del form di creazione.');
    }
}

    public function store(Request $request)
    {
        try {
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

            Adesione::create($request->all());

            return redirect()->route('adesioni.index')->with('success', 'Adesione creata con successo.');
        } catch (Exception $e) {
            \Log::error('Errore durante la creazione dell\'adesione: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Errore durante la creazione dell\'adesione: ' . $e->getMessage()]);
        }
    }

    public function edit(Request $request, $id)    {
        try {
            $adesione = Adesione::findOrFail($id);

            if ($adesione->statoAdesione === 'annullata') {
                return redirect()->route('adesioni.index')->with('error', 'Non è possibile modificare un\'adesione annullata.');
            }

            $eventi = Evento::all();
            $puntiVendita = collect(); // Vuoto di default

            if ($request->has('idEvento') && !empty($request->idEvento)) {
                $eventoId = $request->idEvento;
                $puntiVendita = \DB::table('eventopuntivendita')
                    ->join('puntivendita', 'eventopuntivendita.idPuntoVendita', '=', 'puntivendita.id')
                    ->select('puntivendita.*')
                    ->where('eventopuntivendita.idEvento', $eventoId)
                    ->get();
            }else{
                $puntiVendita = \DB::table('eventopuntivendita')
                    ->join('puntivendita', 'eventopuntivendita.idPuntoVendita', '=', 'puntivendita.id')
                    ->select('puntivendita.*')
                    ->where('eventopuntivendita.idEvento', $adesione->idEvento)
                    ->get();
            }

            return view('adesioni.edit', compact('adesione', 'eventi', 'puntiVendita'));
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento del form di modifica: ' . $e->getMessage());
            return redirect()->route('adesioni.index')->with('error', 'Errore durante il caricamento del form di modifica.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $adesione = Adesione::findOrFail($id);

            if ($adesione->statoAdesione === 'annullata') {
                return redirect()->route('adesioni.index')->with('error', 'Non è possibile modificare un\'adesione annullata.');
            }

            if(empty($request->idEvento)){
                $request->merge(['idEvento' => $adesione->idEvento]);
            }

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

            $adesione->update($request->all());

            return redirect()->route('adesioni.index')->with('success', 'Adesione aggiornata con successo.');
        } catch (Exception $e) {
            \Log::error('Errore durante l\'aggiornamento dell\'adesione: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Errore durante l\'aggiornamento dell\'adesione.');
        }
    }

    public function destroy($id)
    {
        try {
            $adesione = Adesione::findOrFail($id);

            if ($adesione->statoAdesione === 'inviata' || $adesione->statoAdesione === 'annullata') {
                return redirect()->route('adesioni.index')->with('error', 'Non è possibile eliminare un\'adesione inviata o annullata.');
            }

            $adesione->delete();

            return redirect()->route('adesioni.index')->with('success', 'Adesione eliminata con successo.');
        } catch (Exception $e) {
            \Log::error('Errore durante l\'eliminazione dell\'adesione: ' . $e->getMessage());
            return redirect()->route('adesioni.index')->with('error', 'Errore durante l\'eliminazione dell\'adesione.');
        }
    }
}