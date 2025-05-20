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

            if ($request->filled('statoEvento')) {
                $stato = $request->input('statoEvento');
                $query->where('statoEvento', $stato);
            }

            $eventi = $query->orderBy('dataInizioEvento', 'desc')->paginate(20);

            return view('eventi.index', compact('eventi'));
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento degli eventi: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Errore durante il caricamento degli eventi: ' . $e->getMessage()]);
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

            $materialiSelezionatiIds = \DB::table('eventomateriali')
                ->where('idEvento', $id)
                ->pluck('idMateriale')
                ->toArray();

            $materiali = \DB::table('materiali')
                ->whereIn('id', $materialiSelezionatiIds)
                ->get();

            return view('eventi.show', compact('evento', 'puntiVendita', 'materiali'));
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento dell\'evento: ' . $e->getMessage());
            return redirect()->route('eventi.index')->withInput()->withErrors(['error' => 'Errore durante il caricamento dell\'evento: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        try {
            $puntiVendita = PuntoVendita::limit(20)->get();
            $materiali = \DB::table('materiali')->limit(20)->get();
            return view('eventi.create', ['puntiVendita' => $puntiVendita], ['materiali' => $materiali]);
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento del form di creazione: ' . $e->getMessage());
            return redirect()->route('eventi.index')->withInput()->withErrors(['error' => 'Errore durante il caricamento del form di creazione: ' . $e->getMessage()]);
        }
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'nomeEvento' => ['required', 'max:255'],
                'annoEvento' => ['required', 'integer', 'min:' . date('Y'), 'max:2100'],
                'dataInizioEvento' => ['required', 'date', 'after_or_equal:dataFineEvento'],
                'dataFineEvento' => ['required', 'date', 'after_or_equal:dataInizioEvento'],
                'richiestaPresenzaPromoter' => ['nullable', 'boolean'],
                'previstaAttivitaDiCaricamento' => ['nullable', 'boolean'],
                'previstaAttivitaDiAllestimento' => ['nullable', 'boolean'],
                'selectedPuntiVendita' => ['required', 'array', 'min:1'],
                'selectedPuntiVendita.*' => ['exists:puntivendita,id'],
                'selectedMateriali' => ['required', 'array', 'min:1'],
                'selectedMateriali.*' => ['exists:materiali,id'],
            ], [
                'nomeEvento.required' => 'Il nome dell\'evento è obbligatorio.',
                'nomeEvento.max' => 'Il nome dell\'evento non può superare 255 caratteri.',
                'annoEvento.required' => 'L\'anno dell\'evento è obbligatorio.',
                'annoEvento.integer' => 'L\'anno dell\'evento deve essere un numero intero.',
                'annoEvento.min' => 'L\'anno dell\'evento non può essere precedente all\'anno corrente.',
                'annoEvento.max' => 'L\'anno dell\'evento non può superare il 2100.',
                'dataInizioEvento.required' => 'La data di inizio evento è obbligatoria.',
                'dataInizioEvento.date' => 'La data di inizio evento deve essere una data valida.',
                'dataInizioEvento.after_or_equal' => 'La data di inizio evento deve essere uguale o successiva alla data di fine.',
                'dataFineEvento.required' => 'La data di fine evento è obbligatoria.',
                'dataFineEvento.date' => 'La data di fine evento deve essere una data valida.',
                'dataFineEvento.after_or_equal' => 'La data di fine evento deve essere uguale o successiva alla data di inizio.',
                'richiestaPresenzaPromoter.boolean' => 'Il campo richiesta presenza promoter deve essere vero o falso.',
                'previstaAttivitaDiCaricamento.boolean' => 'Il campo prevista attività di caricamento deve essere vero o falso.',
                'previstaAttivitaDiAllestimento.boolean' => 'Il campo prevista attività di allestimento deve essere vero o falso.',
                'selectedPuntiVendita.required' => 'Seleziona almeno un punto vendita.',
                'selectedPuntiVendita.array' => 'Il campo punti vendita deve essere un array.',
                'selectedPuntiVendita.min' => 'Seleziona almeno un punto vendita.',
                'selectedPuntiVendita.*.exists' => 'Uno o più punti vendita selezionati non sono validi.',
                'selectedMateriali.required' => 'Seleziona almeno un materiale.',
                'selectedMateriali.*.exists' => 'Uno o più materiali selezionati non sono validi.',
                'selectedMateriali.min' => 'Seleziona almeno un materiale.',
            ]);

            $request->merge([
                'idUtenteCreatoreEvento' => Auth::user()->id,
                'dataInserimentoEvento' => now('Europe/Rome'),
                'statoEvento' => 'creato',
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

            if ($request->filled('selectedMateriali')) {
                $materialiIds = $request->input('selectedMateriali');
                $insertData = [];
                foreach ($materialiIds as $idMateriale) {
                    $insertData[] = [
                        'idEvento' => $evento->id,
                        'idMateriale' => $idMateriale,
                    ];
                }
                if (!empty($insertData)) {
                    \DB::table('eventomateriali')->insert($insertData);
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
            $materiali = \DB::table('materiali')->limit(20)->get();

            // ID dei punti vendita già associati all'evento tramite tabella pivot eventopuntivendita
            $puntiVenditaSelezionatiIds = \DB::table('eventopuntivendita')
                ->where('idEvento', $id)
                ->pluck('idPuntoVendita')
                ->toArray();

            // Recupera i dati completi dei punti vendita selezionati per mostrare label ecc.
            $puntiVenditaSelezionati = PuntoVendita::whereIn('id', $puntiVenditaSelezionatiIds)->get();

            // ID dei materiali già associati all'evento tramite tabella pivot eventomateriali
            $materialiSelezionatiIds = \DB::table('eventomateriali')
                ->where('idEvento', $id)
                ->pluck('idMateriale')
                ->toArray();
            
            // Recupera i dati completi dei materiali selezionati per mostrare label ecc.
            $materialiSelezionati = \DB::table('materiali')
                ->whereIn('id', $materialiSelezionatiIds)
                ->get();

            return view('eventi.edit', compact('evento', 'puntiVendita', 'puntiVenditaSelezionati', 'materiali', 'materialiSelezionati'));
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento del form di modifica: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Errore durante il caricamento del form di modifica: ' . $e->getMessage()]);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $evento = Evento::findOrFail($id);

            $data = $request->validate([
                'nomeEvento' => 'required|string',
                'annoEvento' => 'required|integer|min:' . date('Y') . '|max:2100',
                'dataInizioEvento' => 'required|date|before_or_equal:dataFineEvento',
                'dataFineEvento' => 'required|date|after_or_equal:dataInizioEvento',
                'richiestaPresenzaPromoter' => 'required|boolean',
                'previstaAttivitaDiCaricamento' => 'required|boolean',
                'previstaAttivitaDiAllestimento' => 'required|boolean',
                'selectedPuntiVendita' => 'required|array|min:1',
                'selectedPuntiVendita.*' => 'integer|exists:puntivendita,id',
                'selectedMateriali' => 'required|array|min:1',
                'selectedMateriali.*' => 'integer|exists:materiali,id',
            ], [
                'nomeEvento.required' => 'Il nome dell\'evento è obbligatorio.',
                'nomeEvento.string' => 'Il nome dell\'evento deve essere una stringa.',
                'annoEvento.required' => 'L\'anno dell\'evento è obbligatorio.',
                'annoEvento.integer' => 'L\'anno dell\'evento deve essere un numero intero.',
                'annoEvento.min' => 'L\'anno dell\'evento non può essere precedente all\'anno corrente.',
                'annoEvento.max' => 'L\'anno dell\'evento non può superare il 2100.',
                'dataFineEvento.required' => 'La data di fine evento è obbligatoria.',
                'dataInizioEvento.required' => 'La data di inizio evento è obbligatoria.',
                'dataInizioEvento.date' => 'La data di inizio evento deve essere una data valida.',
                'dataInizioEvento.before_or_equal' => 'La data di inizio evento deve essere uguale o successiva alla data di fine.',
                'dataFineEvento.date' => 'La data di fine evento deve essere una data valida.',
                'dataFineEvento.after_or_equal' => 'La data di fine evento deve essere uguale o successiva alla data di inizio.',
                'richiestaPresenzaPromoter.required' => 'Il campo richiesta presenza promoter è obbligatorio.',
                'richiestaPresenzaPromoter.boolean' => 'Il campo richiesta presenza promoter deve essere vero o falso.',
                'previstaAttivitaDiCaricamento.required' => 'Il campo prevista attività di caricamento è obbligatorio.',
                'previstaAttivitaDiCaricamento.boolean' => 'Il campo prevista attività di caricamento deve essere vero o falso.',
                'previstaAttivitaDiAllestimento.required' => 'Il campo prevista attività di allestimento è obbligatorio.',
                'previstaAttivitaDiAllestimento.boolean' => 'Il campo prevista attività di allestimento deve essere vero o falso.',
                'selectedPuntiVendita.required' => 'Seleziona almeno un punto vendita.',
                'selectedPuntiVendita.array' => 'Il campo punti vendita deve essere un array.',
                'selectedPuntiVendita.min' => 'Seleziona almeno un punto vendita.',
                'selectedPuntiVendita.*.integer' => 'L\'ID del punto vendita deve essere un numero intero.',
                'selectedPuntiVendita.*.exists' => 'Uno o più punti vendita selezionati non sono validi.',
                'selectedMateriali.required' => 'Seleziona almeno un materiale.',
                'selectedMateriali.array' => 'Il campo materiali deve essere un array.',
                'selectedMateriali.min' => 'Seleziona almeno un materiale.',
                'selectedMateriali.*.integer' => 'L\'ID del materiale deve essere un numero intero.',
                'selectedMateriali.*.exists' => 'Uno o più materiali selezionati non sono validi.',
            ]);

            // Aggiorna dati evento
            $evento->update($data);

            // Aggiorna pivot eventopuntivendita: cancella quelli vecchi e inserisci quelli nuovi
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

            if (!empty($data['selectedMateriali'])) {
                // Aggiorna pivot eventomateriali: cancella quelli vecchi e inserisci quelli nuovi
                \DB::table('eventomateriali')->where('idEvento', $evento->id)->delete();

                $insert = [];
                foreach ($data['selectedMateriali'] as $idMateriale) {
                    $insert[] = [
                        'idEvento' => $evento->id,
                        'idMateriale' => $idMateriale,
                    ];
                }
                \DB::table('eventomateriali')->insert($insert);
            }

            return redirect()->route('eventi.index')->with('success', 'Evento aggiornato con successo.');
        } catch (Exception $e) {
            \Log::error('Errore durante la modifica dell\'evento: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Errore durante la modifica dell\'evento: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $evento = Evento::findOrFail($id);

            //set statoEvento a "annullato"
            $evento->update(['statoEvento' => 'annullato']);

            return redirect()->route('eventi.index')->with('success', 'Evento eliminato con successo.');
        } catch (Exception $e) {
            \Log::error('Errore durante l\'eliminazione dell\'evento: ' . $e->getMessage());
            return redirect()->route('eventi.index')->withInput()->withErrors(['error' => 'Errore durante l\'eliminazione dell\'evento: ' . $e->getMessage()]);
        }
    }
}
