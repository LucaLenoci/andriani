<?php

namespace App\Http\Controllers;
use App\Models\Evento;
use App\Models\PuntoVendita;
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

            // Recupera gli id dei punti vendita associati
            $puntiVenditaSelezionatiIds = \DB::table('eventopuntivendita')
                ->where('idEvento', $id)
                ->pluck('idPuntoVendita')
                ->toArray();

            $ids = collect($puntiVenditaSelezionatiIds)->pluck('id')->toArray();

    // Recupera i dati completi dei punti vendita associati
            $puntiVendita = \DB::table('puntivendita')
                ->whereIn('id', $puntiVenditaSelezionatiIds)
                ->get();

            return view('eventi.show', compact('evento', 'puntiVendita'));
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento dell\'evento: ' . $e->getMessage());
            return redirect()->route('eventi.index')->with('error', 'Errore durante il caricamento dell\'evento.');
        }
    }

    public function create()
    {
        try {
            $puntiVendita = PuntoVendita::limit(20)->get();
            return view('eventi.create', ['puntiVendita' => $puntiVendita]);
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
                'selectedPuntiVendita' => 'array|nullable',
                'selectedPuntiVendita.*' => 'exists:puntivendita,id',
            ]);

            $request->merge([
                'idUtenteCreatoreEvento' => Auth::user()->id,
                'dataInserimentoEvento' => now('Europe/Rome'),
            ]);

            $evento = Evento::create($request->except('selectedPuntiVendita'));

            if ($request->filled('selectedPuntiVendita')) {
                $puntiVenditaIds = $request->input('selectedPuntiVendita');
                $insertData = [];
                foreach ($puntiVenditaIds as $idPuntoVendita) {
                    $insertData[] = [
                        'idEvento' => $evento->id,
                        'idPuntoVendita' => $idPuntoVendita,
                    ];
                }
                if (!empty($insertData)) {
                    \DB::table('eventopuntivendita')->insert($insertData);
                }
            }

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
            $puntiVendita = PuntoVendita::limit(20)->get();

            // ID dei punti vendita già associati all'evento tramite tabella pivot eventopuntivendita
            $puntiVenditaSelezionatiIds = \DB::table('eventopuntivendita')
                ->where('idEvento', $id)
                ->pluck('idPuntoVendita')
                ->toArray();

            // Recupera i dati completi dei punti vendita selezionati per mostrare label ecc.
            $puntiVenditaSelezionati = PuntoVendita::whereIn('id', $puntiVenditaSelezionatiIds)->get();

            return view('eventi.edit', compact('evento', 'puntiVendita', 'puntiVenditaSelezionati'));
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento del form di modifica: ' . $e->getMessage());
            return redirect()->route('eventi.index')->with('error', 'Errore durante il caricamento del form di modifica.');
        }
    }


    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);

        $data = $request->validate([
            'nomeEvento' => 'required|string',
            'annoEvento' => 'required|integer',
            'dataInizioEvento' => 'required|date',
            'dataFineEvento' => 'nullable|date',
            'richiestaPresenzaPromoter' => 'required|boolean',
            'previstaAttivitaDiCaricamento' => 'required|boolean',
            'previstaAttivitaDiAllestimento' => 'required|boolean',
            'selectedPuntiVendita' => 'array',
            'selectedPuntiVendita.*' => 'integer|exists:puntivendita,id',
        ]);

        // Aggiorna dati evento
        $evento->update($data);

        // Aggiorna pivot eventipuntivendita: cancella quelli vecchi e inserisci quelli nuovi
        \DB::table('eventopuntivendita')->where('idEvento', $evento->id)->delete();

        if (!empty($data['selectedPuntiVendita'])) {
            $insert = [];
            foreach ($data['selectedPuntiVendita'] as $idPuntoVendita) {
                $insert[] = [
                    'idEvento' => $evento->id,
                    'idPuntoVendita' => $idPuntoVendita,
                ];
            }
            \DB::table('eventopuntivendita')->insert($insert);
        }

        return redirect()->route('eventi.index')->with('success', 'Evento aggiornato correttamente');
    }

    public function destroy($id)
    {
        try {
            $evento = Evento::findOrFail($id);

            // Elimina le associazioni nella tabella pivot
            \DB::table('eventopuntivendita')->where('idEvento', $evento->id)->delete();

            $evento->delete();

            return redirect()->route('eventi.index')->with('success', 'Evento eliminato con successo.');
        } catch (Exception $e) {
            \Log::error('Errore durante l\'eliminazione dell\'evento: ' . $e->getMessage());
            return redirect()->route('eventi.index')->with('error', 'Errore durante l\'eliminazione dell\'evento.');
        }
    }
}
