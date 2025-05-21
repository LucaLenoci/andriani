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

            // Filtro per ricerca testuale
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

            // Filtro per statoAdesione
            if ($request->filled('statoAdesione')) {
                $stati = $request->input('statoAdesione');
                if (is_array($stati)) {
                    $query->whereIn('statoAdesione', $stati);
                } else {
                    $query->where('statoAdesione', $stati);
                }
            }

            $adesioni = $query->orderBy('dataInizioAdesione', 'desc')->paginate(20);

            return view('adesioni.index', compact('adesioni'));
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento delle adesioni: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Errore durante il caricamento delle adesioni: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $adesione = Adesione::findOrFail($id);

            // Carico i materiali associati all'adesione
            $materiali = \DB::table('adesionemateriali')
                ->join('materiali', 'adesionemateriali.idMateriale', '=', 'materiali.id')
                ->select('materiali.*', 'adesionemateriali.*')
                ->where('adesionemateriali.idAdesione', $adesione->id)
                ->get();

            return view('adesioni.show', compact('adesione', 'materiali'));
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento dell\'adesione: ' . $e->getMessage());
            return redirect()->route('adesioni.index')->withInput()->withErrors(['error' => 'Errore durante il caricamento dell\'adesione: ' . $e->getMessage()]);
        }
    }

public function create(Request $request)
{
    try {
        $eventi = Evento::all();
        $puntiVendita = collect(); // Vuoto di default
        $materiali = collect(); // Vuoto di default

        if ($request->has('idEvento') && !empty($request->idEvento)) {
            $eventoId = $request->idEvento;
            $puntiVendita = \DB::table('eventopuntivendita')
                ->join('puntivendita', 'eventopuntivendita.idPuntoVendita', '=', 'puntivendita.id')
                ->select('puntivendita.*')
                ->where('eventopuntivendita.idEvento', $eventoId)
                ->get();

            $materiali = \DB::table('eventomateriali')
                ->join('materiali', 'eventomateriali.idMateriale', '=', 'materiali.id')
                ->select('materiali.*')
                ->where('eventomateriali.idEvento', $eventoId)
                ->get();
        }

        return view('adesioni.create', compact('eventi', 'puntiVendita', 'materiali'));

    } catch (Exception $e) {
        \Log::error('Errore durante il caricamento del form di creazione: ' . $e->getMessage());
        return redirect()->route('adesioni.index')->withInput()->withErrors(['error' => 'Errore durante la creazione dell\'adesione: ' . $e->getMessage()]);
    }
}

    public function store(Request $request)
    {
        try {
            $request->validate([
                'idEvento' => 'required|exists:eventi,id',
                'idPuntoVendita' => 'required|max:255',
                'dataInizioAdesione' => [
                    'required',
                    'date',
                    'before_or_equal:dataFineAdesione',
                    function ($attribute, $value, $fail) use ($request) {
                        $evento = \App\Models\Evento::find($request->idEvento);
                        if ($evento) {
                            $data = \Carbon\Carbon::parse($value);
                            $inizioEvento = \Carbon\Carbon::parse($evento->dataInizioEvento);
                            $fineEvento = \Carbon\Carbon::parse($evento->dataFineEvento);

                            if ($data->lt($inizioEvento) || $data->gt($fineEvento)) {
                                $fail('La data di inizio adesione deve essere compresa tra la data di inizio e fine dell\'evento: ' . $inizioEvento->format('d/m/Y') . ' e ' . $fineEvento->format('d/m/Y'));
                            }
                        }
                    }
                ],
                'dataFineAdesione' => [
                    'required',
                    'date',
                    'after_or_equal:dataInizioAdesione',
                    function ($attribute, $value, $fail) use ($request) {
                        $evento = \App\Models\Evento::find($request->idEvento);
                        if ($evento) {
                            $data = \Carbon\Carbon::parse($value);
                            $inizioEvento = \Carbon\Carbon::parse($evento->dataInizioEvento);
                            $fineEvento = \Carbon\Carbon::parse($evento->dataFineEvento);

                            if ($data->lt($inizioEvento) || $data->gt($fineEvento)) {
                                $fail('La data di fine adesione deve essere compresa tra la data di inizio e fine dell\'evento: ' . $inizioEvento->format('d/m/Y') . ' e ' . $fineEvento->format('d/m/Y'));
                            }
                        }
                    }
                ],
                'autorizzazioneExtraBudget' => 'nullable|string|max:255',
                'richiestaFattibilitaAgenzia' => 'nullable|string|max:255',
                'responsabileCuraAllestimento' => 'nullable|string|max:255',
                'noteAdesione' => 'nullable|string|max:255',
                'materiali' => 'nullable|array',
                'materiali.*.id' => 'required|exists:materiali,id',
                'materiali.*.quantita' => 'required|integer|min:0'
            ], [
                'idEvento.required' => 'Il campo evento è obbligatorio.',
                'idEvento.exists' => 'L\'evento selezionato non esiste.',
                'idPuntoVendita.required' => 'Il campo punto vendita è obbligatorio.',
                'idPuntoVendita.max' => 'Il campo punto vendita non può superare 255 caratteri.',
                'dataInizioAdesione.required' => 'La data di inizio adesione è obbligatoria.',
                'dataInizioAdesione.date' => 'La data di inizio adesione non è valida.',
                'dataInizioAdesione.before_or_equal' => 'La data di inizio adesione deve essere uguale o successiva alla data di fine.',
                'dataFineAdesione.required' => 'La data di fine adesione è obbligatoria.',
                'dataFineAdesione.date' => 'La data di fine adesione non è valida.',
                'dataFineAdesione.after_or_equal' => 'La data di fine adesione deve essere uguale o successiva alla data di inizio.',
                'autorizzazioneExtraBudget.max' => 'Il campo autorizzazione extra budget non può superare 255 caratteri.',
                'richiestaFattibilitaAgenzia.max' => 'Il campo richiesta fattibilità agenzia non può superare 255 caratteri.',
                'responsabileCuraAllestimento.max' => 'Il campo responsabile cura allestimento non può superare 255 caratteri.',
                'noteAdesione.max' => 'Il campo note adesione non può superare 255 caratteri.',
                'materiali.array' => 'I materiali devono essere un array.',
                'materiali.*.id.required' => 'Il campo ID materiale è obbligatorio.',
                'materiali.*.id.exists' => 'Il materiale selezionato non esiste.',
                'materiali.*.quantita.integer' => 'La quantità deve essere un numero intero.',
                'materiali.*.quantita.required' => 'La quantità è obbligatoria.',
                'materiali.*.quantita.min' => 'La quantità non può essere negativa.',
            ]);

            $request->merge([
                'idUtenteCreatoreAdesione' => Auth::user()->id,
                'dataInserimentoAdesione' => now('Europe/Rome'),
                'statoAdesione' => 'bozza'
            ]);

            Adesione::create($request->all());

            // Associa i materiali all'adesione
            if ($request->has('materiali')) {
                foreach ($request->materiali as $materiale) {
                    \DB::table('adesionemateriali')->insert([
                        'idAdesione' => Adesione::latest('id')->first()->id,
                        'idMateriale' => $materiale['id'],
                        'quantitaRichiesta' => $materiale['quantita'] ?? 0, // Default a 1 se non specificato
                    ]);
                }
            }            

            return redirect()->route('adesioni.index')->with('success', 'Adesione creata con successo.');
        } catch (Exception $e) {
            \Log::error('Errore durante la creazione dell\'adesione: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Errore durante la creazione dell\'adesione: ' . $e->getMessage()]);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $adesione = Adesione::findOrFail($id);

            if ($adesione->statoAdesione === 'annullata') {
                return redirect()->route('adesioni.index')->with('error', 'Non è possibile modificare un\'adesione annullata.');
            }

            // L'evento NON è modificabile: carico solo quello dell'adesione
            $evento = Evento::find($adesione->idEvento);
            $eventi = collect([$evento]); // Solo l'evento associato

            // Carico i punti vendita relativi all'evento dell'adesione
            $puntiVendita = \DB::table('eventopuntivendita')
                ->join('puntivendita', 'eventopuntivendita.idPuntoVendita', '=', 'puntivendita.id')
                ->select('puntivendita.*')
                ->where('eventopuntivendita.idEvento', $adesione->idEvento)
                ->get();

            // Carico i materiali associati all'adesione
            $materiali = \DB::table('adesionemateriali')
                ->join('materiali', 'adesionemateriali.idMateriale', '=', 'materiali.id')
                ->select('materiali.*', 'adesionemateriali.*')
                ->where('adesionemateriali.idAdesione', $adesione->id)
                ->get();

            return view('adesioni.edit', compact('adesione', 'eventi', 'puntiVendita', 'materiali'));
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento del form di modifica: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Errore durante il caricamento del form di modifica: ' . $e->getMessage()]);
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
                'idEvento' => [
                    'required',
                    'exists:eventi,id'
                ],
                'idPuntoVendita' => [
                    'required',
                    'max:255'
                ],
                'dataInizioAdesione' => [
                    'required',
                    'date',
                    'before_or_equal:dataFineAdesione',
                    function ($attribute, $value, $fail) use ($request) {
                        $evento = \App\Models\Evento::find($request->idEvento);
                        if ($evento) {
                            $data = \Carbon\Carbon::parse($value);
                            $inizioEvento = \Carbon\Carbon::parse($evento->dataInizioEvento);
                            $fineEvento = \Carbon\Carbon::parse($evento->dataFineEvento);

                            if ($data->lt($inizioEvento) || $data->gt($fineEvento)) {
                                $fail('La data di inizio adesione deve essere compresa tra la data di inizio e fine dell\'evento: ' . $inizioEvento->format('d/m/Y') . ' e ' . $fineEvento->format('d/m/Y'));
                            }
                        }
                    }
                ],
                'dataFineAdesione' => [
                    'required',
                    'date',
                    'after_or_equal:dataInizioAdesione',
                    function ($attribute, $value, $fail) use ($request) {
                        $evento = \App\Models\Evento::find($request->idEvento);
                        if ($evento) {
                            $data = \Carbon\Carbon::parse($value);
                            $inizioEvento = \Carbon\Carbon::parse($evento->dataInizioEvento);
                            $fineEvento = \Carbon\Carbon::parse($evento->dataFineEvento);

                            if ($data->lt($inizioEvento) || $data->gt($fineEvento)) {
                                $fail('La data di fine adesione deve essere compresa tra la data di inizio e fine dell\'evento: ' . $inizioEvento->format('d/m/Y') . ' e ' . $fineEvento->format('d/m/Y'));
                            }
                        }
                    }
                ],
                'autorizzazioneExtraBudget' => [
                    'nullable',
                    'string',
                    'max:255'
                ],
                'richiestaFattibilitaAgenzia' => [
                    'nullable',
                    'string',
                    'max:255'
                ],
                'responsabileCuraAllestimento' => [
                    'nullable',
                    'string',
                    'max:255'
                ],
                'noteAdesione' => [
                    'nullable',
                    'string',
                    'max:255'
                ]
                ,
                'materiali' => [
                    'nullable',
                    'array'
                ],
                'materiali.*.idMateriale' => [
                    'required',
                    'exists:materiali,id'
                ],
                'materiali.*.quantitaRichiesta' => [
                    'required',
                    'integer',
                    'min:0'
                ]
            ], [
                'idEvento.required' => 'Il campo evento è obbligatorio.',
                'idEvento.exists' => 'L\'evento selezionato non esiste.',
                'idPuntoVendita.required' => 'Il campo punto vendita è obbligatorio.',
                'idPuntoVendita.max' => 'Il campo punto vendita non può superare 255 caratteri.',
                'dataInizioAdesione.required' => 'La data di inizio adesione è obbligatoria.',
                'dataInizioAdesione.date' => 'La data di inizio adesione non è valida.',
                'dataInizioAdesione.before_or_equal' => 'La data di inizio adesione deve essere uguale o successiva alla data di fine.',
                'dataFineAdesione.required' => 'La data di fine adesione è obbligatoria.',
                'dataFineAdesione.date' => 'La data di fine adesione non è valida.',
                'dataFineAdesione.after_or_equal' => 'La data di fine adesione deve essere uguale o successiva alla data di inizio.',
                'autorizzazioneExtraBudget.max' => 'Il campo autorizzazione extra budget non può superare 255 caratteri.',
                'richiestaFattibilitaAgenzia.max' => 'Il campo richiesta fattibilità agenzia non può superare 255 caratteri.',
                'responsabileCuraAllestimento.max' => 'Il campo responsabile cura allestimento non può superare 255 caratteri.',
                'noteAdesione.max' => 'Il campo note adesione non può superare 255 caratteri.',
                'materiali.array' => 'I materiali devono essere un array.',
                'materiali.*.idMateriale.required' => 'Il campo ID materiale è obbligatorio.',
                'materiali.*.idMateriale.exists' => 'Il materiale selezionato non esiste.',
                'materiali.*.quantitaRichiesta.integer' => 'La quantità deve essere un numero intero.',
                'materiali.*.quantitaRichiesta.required' => 'La quantità è obbligatoria.',
                'materiali.*.quantitaRichiesta.min' => 'La quantità non può essere negativa.',
            ]);

            $adesione->update($request->all());

            // Aggiorna i materiali associati all'adesione
            if ($request->has('materiali')) {
                foreach ($request->materiali as $materiale) {
                    \DB::table('adesionemateriali')->updateOrInsert(
                        [
                            'idAdesione' => $adesione->id,
                            'idMateriale' => $materiale['idMateriale']
                        ],
                        [
                            'quantitaRichiesta' => $materiale['quantitaRichiesta'] ?? 0 // Default a 1 se non specificato
                        ]
                    );
                }
            }

            return redirect()->route('adesioni.index')->with('success', 'Adesione aggiornata con successo.');
        } catch (Exception $e) {
            \Log::error('Errore durante l\'aggiornamento dell\'adesione: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Errore durante l\'aggiornamento dell\'adesione.' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $adesione = Adesione::findOrFail($id);

            if ($adesione->statoAdesione === 'inviata' || $adesione->statoAdesione === 'annullata') {
                return redirect()->route('adesioni.index')->with('error', 'Non è possibile eliminare un\'adesione inviata o annullata.');
            }

            $adesione->statoAdesione = 'annullata';
            $adesione->save();

            return redirect()->route('adesioni.index')->with('success', 'Adesione eliminata con successo.');
        } catch (Exception $e) {
            \Log::error('Errore durante l\'eliminazione dell\'adesione: ' . $e->getMessage());
            return redirect()->route('adesioni.index')->withInput()->withErrors(['error' => 'Errore durante l\'eliminazione dell\'adesione: ' . $e->getMessage()]);
        }
    }
}