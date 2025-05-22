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
        $eventoCorrente = null; // Vuoto di default

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
            
            $eventoCorrente = Evento::find($eventoId);
        }

        return view('adesioni.create', compact('eventi', 'puntiVendita', 'materiali', 'eventoCorrente'));

    } catch (Exception $e) {
        \Log::error('Errore durante il caricamento del form di creazione: ' . $e->getMessage());
        return redirect()->route('adesioni.index')->withInput()->withErrors(['error' => 'Errore durante la creazione dell\'adesione: ' . $e->getMessage()]);
    }
}

    public function store(Request $request)
    {
        try {

            // Controllo se le giornate sono passate come JSON e le converto in array
            // Se le giornate sono passate come JSON, le decodifico e le aggiungo alla richiesta    
            // Questo passaggio si è reso necessario per ovviare agli errori che si verificano quando le giornate vengonivano passate come Array
            if ($request->filled('giornate_json_caricamento')) {
                $giornateCaricamento = json_decode($request->input('giornate_json_caricamento'), true);
                if (is_array($giornateCaricamento)) {
                    $request->merge(['giornate_caricamento' => $giornateCaricamento]);
                }
            }
            if ($request->filled('giornate_json_promoter')) {
                $giornatePromoter = json_decode($request->input('giornate_json_promoter'), true);
                if (is_array($giornatePromoter)) {
                    $request->merge(['giornate_promoter' => $giornatePromoter]);
                }
            }
            if ($request->filled('giornate_json_allestimento')) {
                $giornateAllestimento = json_decode($request->input('giornate_json_allestimento'), true);
                if (is_array($giornateAllestimento)) {
                    $request->merge(['giornate_allestimento' => $giornateAllestimento]);
                }
            }

            //dd(request()->all());

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
                ,
                'giornate_promoter' => 'nullable|array',
                'giornate_promoter.*.data' => [
                    'required',
                    'date',
                
                // Custom validation: all dates must be unique
                function ($attribute, $value, $fail) use ($request) {
                    // Controllo che la data sia nell'intervallo dell'adesione
                        $dataInizio = $request->input('dataInizioAdesione');
                        $dataFine = $request->input('dataFineAdesione');
                        if ($dataInizio && $dataFine) {
                            $data = \Carbon\Carbon::parse($value);
                            $inizio = \Carbon\Carbon::parse($dataInizio);
                            $fine = \Carbon\Carbon::parse($dataFine);
                            if ($data->lt($inizio) || $data->gt($fine)) {
                                $fail('La data di promoter deve essere compresa tra la data di inizio e fine adesione: ' . $inizio->format('d/m/Y') . ' e ' . $fine->format('d/m/Y'));
                            }
                        }
                        // Custom validation: all dates must be unique
                        $dates = array_column($request->input('giornate_promoter', []), 'data');
                        if (count($dates) !== count(array_unique($dates))) {
                            $fail('Le date delle giornate di promoter devono essere tutte diverse.');
                        }
                }],
                'giornate_promoter.*.orarioInizio' => 'required|date_format:H:i',
                'giornate_promoter.*.orarioInizio' => 'required|date_format:H:i|before_or_equal:giornate_promoter.*.orarioFine',
                'giornate_promoter.*.orarioFine' => 'required|date_format:H:i',
                'giornate_promoter.*.orarioFine' => 'required|date_format:H:i|after_or_equal:giornate_promoter.*.orarioInizio',
                'giornate_promoter.*.minutiTotali' => 'required|integer|min:0',
                'giornate_promoter.*.numeroRisorseRichieste' => 'required|integer|min:1',
                'giornate_caricamento' => 'nullable|array',
                'giornate_caricamento.*.data' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) use ($request) {
                        // Controllo che la data sia nell'intervallo dell'adesione
                        $dataInizio = $request->input('dataInizioAdesione');
                        $dataFine = $request->input('dataFineAdesione');
                        if ($dataInizio && $dataFine) {
                            $data = \Carbon\Carbon::parse($value);
                            $inizio = \Carbon\Carbon::parse($dataInizio);
                            $fine = \Carbon\Carbon::parse($dataFine);
                            if ($data->lt($inizio) || $data->gt($fine)) {
                                $fail('La data di caricamento deve essere compresa tra la data di inizio e fine adesione: ' . $inizio->format('d/m/Y') . ' e ' . $fine->format('d/m/Y'));
                            }
                        }
                        // Custom validation: all dates must be unique
                        $dates = array_column($request->input('giornate_caricamento', []), 'data');
                        if (count($dates) !== count(array_unique($dates))) {
                            $fail('Le date delle giornate di caricamento devono essere tutte diverse.');
                        }
                    }],
                'giornate_caricamento.*.orarioInizio' => 'required|date_format:H:i',
                'giornate_caricamento.*.orarioFine' => 'required|date_format:H:i',
                'giornate_caricamento.*.minutiTotali' => 'required|integer|min:0',
                'giornate_caricamento.*.numeroRisorseRichieste' => 'required|integer|min:1',
                'giornate_allestimento' => 'nullable|array',
                'giornate_allestimento.*.data' => [
                    'required',
                    'date',
                
                // Custom validation: all dates must be unique
                function ($attribute, $value, $fail) use ($request) {
                    // Controllo che la data sia nell'intervallo dell'adesione
                    $dataInizio = $request->input('dataInizioAdesione');
                    $dataFine = $request->input('dataFineAdesione');
                    if ($dataInizio && $dataFine) {
                        $data = \Carbon\Carbon::parse($value);
                        $inizio = \Carbon\Carbon::parse($dataInizio);
                        $fine = \Carbon\Carbon::parse($dataFine);
                        if ($data->lt($inizio) || $data->gt($fine)) {
                            $fail('La data di allestimento deve essere compresa tra la data di inizio e fine adesione: ' . $inizio->format('d/m/Y') . ' e ' . $fine->format('d/m/Y'));
                        }
                    }
                    // Custom validation: all dates must be unique
                    $dates = array_column($request->input('giornate_allestimento', []), 'data');
                    if (count($dates) !== count(array_unique($dates))) {
                        $fail('Le date delle giornate di allestimento devono essere tutte diverse.');
                    }
                }],
                'giornate_allestimento.*.orarioInizio' => 'required|date_format:H:i',
                'giornate_allestimento.*.orarioFine' => 'required|date_format:H:i',
                'giornate_allestimento.*.minutiTotali' => 'required|integer|min:0',
                'giornate_allestimento.*.numeroRisorseRichieste' => 'required|integer|min:1'
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
                'giornate_promoter.array' => 'Le giornate promoter devono essere un array.',
                'giornate_promoter.*.data.required' => 'La data è obbligatoria.',
                'giornate_promoter.*.data.date' => 'La data non è valida.',
                'giornate_promoter.*.orarioInizio.required' => 'L\'orario di inizio è obbligatorio.',
                'giornate_promoter.*.orarioInizio.date_format' => 'L\'orario di inizio non è valido.',
                'giornate_promoter.*.orarioFine.required' => 'L\'orario di fine è obbligatorio.',
                'giornate_promoter.*.orarioFine.date_format' => 'L\'orario di fine non è valido.',
                'giornate_promoter.*.minutiTotali.required' => 'I minuti totali sono obbligatori.',
                'giornate_promoter.*.minutiTotali.integer' => 'I minuti totali devono essere un numero intero.',
                'giornate_promoter.*.minutiTotali.min' => 'I minuti totali non possono essere negativi.',
                'giornate_promoter.*.numeroRisorseRichieste.required' => 'Il numero di risorse richieste è obbligatorio.',
                'giornate_promoter.*.numeroRisorseRichieste.integer' => 'Il numero di risorse richieste deve essere un numero intero.',
                'giornate_promoter.*.numeroRisorseRichieste.min' => 'Il numero di risorse richieste non può essere negativo.',
                'giornate_caricamento.array' => 'Le giornate di caricamento devono essere un array.',
                'giornate_caricamento.*.orarioFine.after' => 'L\'orario di fine deve essere successivo all\'orario di inizio.',
                'giornate_caricamento.*.orarioInizio.before' => 'L\'orario di inizio deve essere precedente all\'orario di fine.',
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

            // Aggiungi le giornate
            // Salva tutte le giornate per ogni esigenza (promoter, caricamento, allestimento)
            $esigenze = ['promoter', 'caricamento', 'allestimento'];
            $idAdesione = Adesione::latest('id')->first()->id;

            foreach ($esigenze as $esigenza) {
                $giornateKey = 'giornate_' . $esigenza;
                if ($request->has($giornateKey)) {
                    foreach ($request->input($giornateKey) as $giornata) {
                        \DB::table('giornate')->insert([
                            'idAdesione' => $idAdesione,
                            'dataGiornata' => $giornata['data'] ?? null,
                            'orarioInizioGiornata' => $giornata['orarioInizio'] ?? null,
                            'orarioFineGiornata' => $giornata['orarioFine'] ?? null,
                            'minutiTotaliGiornata' => $giornata['minutiTotali'] ?? null,
                            'numeroRisorseRichieste' => $giornata['numeroRisorseRichieste'] ?? null,
                            'esigenzaGiornata' => $esigenza,
                            'idUtenteCreatoreGiornata' => Auth::user()->id,
                            'dataInserimentoGiornata' => now('Europe/Rome'),
                        ]);
                    }
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

            $giornate = [
                'promoter' => \DB::table('giornate')
                    ->where('idAdesione', $adesione->id)
                    ->where('esigenzaGiornata', 'promoter')
                    ->get(),
                'caricamento' => \DB::table('giornate')
                    ->where('idAdesione', $adesione->id)
                    ->where('esigenzaGiornata', 'caricamento')
                    ->get(),
                'allestimento' => \DB::table('giornate')
                    ->where('idAdesione', $adesione->id)
                    ->where('esigenzaGiornata', 'allestimento')
                    ->get(),
            ];

            //dalle in json le giornate
            $giornate['promoter'] = json_encode($giornate['promoter']);
            $giornate['caricamento'] = json_encode($giornate['caricamento']);
            $giornate['allestimento'] = json_encode($giornate['allestimento']);


            return view('adesioni.edit', compact('adesione', 'eventi', 'puntiVendita', 'materiali', 'giornate'));
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento del form di modifica: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Errore durante il caricamento del form di modifica: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            // Controllo se le giornate sono passate come JSON e le converto in array
            // Se le giornate sono passate come JSON, le decodifico e le aggiungo alla richiesta    
            // Questo passaggio si è reso necessario per ovviare agli errori che si verificano quando le giornate vengonivano passate come Array
            if ($request->filled('giornate_json_caricamento')) {
                $giornateCaricamento = json_decode($request->input('giornate_json_caricamento'), true);
                if (is_array($giornateCaricamento)) {
                    foreach ($giornateCaricamento as &$giornata) {
                        if (!empty($giornata['orarioInizioGiornata'])) {
                            $giornata['orarioInizioGiornata'] = date('H:i', strtotime($giornata['orarioInizioGiornata']));
                        }
                        if (!empty($giornata['orarioFineGiornata'])) {
                            $giornata['orarioFineGiornata'] = date('H:i', strtotime($giornata['orarioFineGiornata']));
                        }
                        if (!isset($giornata['idUtenteCreatoreGiornata'])) {
                            $giornata['idUtenteCreatoreGiornata'] = Auth::user()->id;
                        }
                        if (!isset($giornata['dataInserimentoGiornata'])) {
                            $giornata['dataInserimentoGiornata'] = now('Europe/Rome')->format('Y-m-d H:i:s');
                        }
                        if (!isset($giornata['idUtenteModificatoreGiornata'])) {
                            $giornata['idUtenteModificatoreGiornata'] = null;
                        }
                        if (!isset($giornata['dataModificaGiornata'])) {
                            $giornata['dataModificaGiornata'] = null;
                        }

                    }
                    $request->merge(['giornate_caricamento' => $giornateCaricamento]);
                }
            }
            if ($request->filled('giornate_json_promoter')) {
                $giornatePromoter = json_decode($request->input('giornate_json_promoter'), true);
                if (is_array($giornatePromoter)) {
                    foreach ($giornatePromoter as &$giornata) {
                        if (!empty($giornata['orarioInizioGiornata'])) {
                            $giornata['orarioInizioGiornata'] = date('H:i', strtotime($giornata['orarioInizioGiornata']));
                        }
                        if (!empty($giornata['orarioFineGiornata'])) {
                            $giornata['orarioFineGiornata'] = date('H:i', strtotime($giornata['orarioFineGiornata']));
                        }
                        if (!isset($giornata['idUtenteCreatoreGiornata'])) {
                            $giornata['idUtenteCreatoreGiornata'] = Auth::user()->id;
                        }
                        if (!isset($giornata['dataInserimentoGiornata'])) {
                            $giornata['dataInserimentoGiornata'] = now('Europe/Rome')->format('Y-m-d H:i:s');
                        }
                        if (!isset($giornata['idUtenteModificatoreGiornata'])) {
                            $giornata['idUtenteModificatoreGiornata'] = null;
                        }
                        if (!isset($giornata['dataModificaGiornata'])) {
                            $giornata['dataModificaGiornata'] = null;
                        }
                    }
                    $request->merge(['giornate_promoter' => $giornatePromoter]);
                }
            }
            if ($request->filled('giornate_json_allestimento')) {
                $giornateAllestimento = json_decode($request->input('giornate_json_allestimento'), true);
                if (is_array($giornateAllestimento)) {
                    foreach ($giornateAllestimento as &$giornata) {
                        if (!empty($giornata['orarioInizioGiornata'])) {
                            $giornata['orarioInizioGiornata'] = date('H:i', strtotime($giornata['orarioInizioGiornata']));
                        }
                        if (!empty($giornata['orarioFineGiornata'])) {
                            $giornata['orarioFineGiornata'] = date('H:i', strtotime($giornata['orarioFineGiornata']));
                        }
                        if (!isset($giornata['idUtenteCreatoreGiornata'])) {
                            $giornata['idUtenteCreatoreGiornata'] = Auth::user()->id;
                        }
                        if (!isset($giornata['dataInserimentoGiornata'])) {
                            $giornata['dataInserimentoGiornata'] = now('Europe/Rome')->format('Y-m-d H:i:s');
                        }
                        if (!isset($giornata['idUtenteModificatoreGiornata'])) {
                            $giornata['idUtenteModificatoreGiornata'] = null;
                        }
                        if (!isset($giornata['dataModificaGiornata'])) {
                            $giornata['dataModificaGiornata'] = null;
                        }
                    }
                    $request->merge(['giornate_allestimento' => $giornateAllestimento]);
                }
            }

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
                ],
                'giornate_promoter' => [
                    'nullable',
                    'array'
                ],
                'giornate_promoter.*.dataGiornata' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) use ($request) {
                        $dataInizio = $request->input('dataInizioAdesione');
                        $dataFine = $request->input('dataFineAdesione');
                        if ($dataInizio && $dataFine) {
                            $data = \Carbon\Carbon::parse($value);
                            $inizio = \Carbon\Carbon::parse($dataInizio);
                            $fine = \Carbon\Carbon::parse($dataFine);
                            if ($data->lt($inizio) || $data->gt($fine)) {
                                $fail('La data di promoter deve essere compresa tra la data di inizio e fine adesione: ' . $inizio->format('d/m/Y') . ' e ' . $fine->format('d/m/Y'));
                            }
                        }
                        $dates = array_column($request->input('giornate_promoter', []), 'dataGiornata');
                        if (count($dates) !== count(array_unique($dates))) {
                            $fail('Le date delle giornate di promoter devono essere tutte diverse.');
                        }
                    }
                ],
                'giornate_promoter.*.orarioInizioGiornata' => [
                    'required',
                    'date_format:H:i',
                    'before_or_equal:giornate_promoter.*.orarioFineGiornata'
                ],
                'giornate_promoter.*.orarioFineGiornata' => [
                    'required',
                    'date_format:H:i',
                    'after_or_equal:giornate_promoter.*.orarioInizioGiornata'
                ],
                'giornate_promoter.*.minutiTotaliGiornata' => [
                    'required',
                    'integer',
                    'min:0'
                ],
                'giornate_promoter.*.numeroRisorseRichieste' => [
                    'required',
                    'integer',
                    'min:1'
                ],
                'giornate_caricamento' => [
                    'nullable',
                    'array'
                ],
                'giornate_caricamento.*.dataGiornata' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) use ($request) {
                        $dataInizio = $request->input('dataInizioAdesione');
                        $dataFine = $request->input('dataFineAdesione');
                        if ($dataInizio && $dataFine) {
                            $data = \Carbon\Carbon::parse($value);
                            $inizio = \Carbon\Carbon::parse($dataInizio);
                            $fine = \Carbon\Carbon::parse($dataFine);
                            if ($data->lt($inizio) || $data->gt($fine)) {
                                $fail('La data di caricamento deve essere compresa tra la data di inizio e fine adesione: ' . $inizio->format('d/m/Y') . ' e ' . $fine->format('d/m/Y'));
                            }
                        }
                        $dates = array_column($request->input('giornate_caricamento', []), 'dataGiornata');
                        if (count($dates) !== count(array_unique($dates))) {
                            $fail('Le date delle giornate di caricamento devono essere tutte diverse.');
                        }
                    }
                ],
                'giornate_caricamento.*.orarioInizioGiornata' => [
                    'required',
                    'date_format:H:i'
                ],
                'giornate_caricamento.*.orarioFineGiornata' => [
                    'required',
                    'date_format:H:i'
                ],
                'giornate_caricamento.*.minutiTotaliGiornata' => [
                    'required',
                    'integer',
                    'min:0'
                ],
                'giornate_caricamento.*.numeroRisorseRichieste' => [
                    'required',
                    'integer',
                    'min:1'
                ],
                'giornate_allestimento' => [
                    'nullable',
                    'array'
                ],
                'giornate_allestimento.*.dataGiornata' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) use ($request) {
                        $dataInizio = $request->input('dataInizioAdesione');
                        $dataFine = $request->input('dataFineAdesione');
                        if ($dataInizio && $dataFine) {
                            $data = \Carbon\Carbon::parse($value);
                            $inizio = \Carbon\Carbon::parse($dataInizio);
                            $fine = \Carbon\Carbon::parse($dataFine);
                            if ($data->lt($inizio) || $data->gt($fine)) {
                                $fail('La data di allestimento deve essere compresa tra la data di inizio e fine adesione: ' . $inizio->format('d/m/Y') . ' e ' . $fine->format('d/m/Y'));
                            }
                        }
                        $dates = array_column($request->input('giornate_allestimento', []), 'dataGiornata');
                        if (count($dates) !== count(array_unique($dates))) {
                            $fail('Le date delle giornate di allestimento devono essere tutte diverse.');
                        }
                    }
                ],
                'giornate_allestimento.*.orarioInizioGiornata' => [
                    'required',
                    'date_format:H:i'
                ],
                'giornate_allestimento.*.orarioFineGiornata' => [
                    'required',
                    'date_format:H:i'
                ],
                'giornate_allestimento.*.minutiTotaliGiornata' => [
                    'required',
                    'integer',
                    'min:0'
                ],
                'giornate_allestimento.*.numeroRisorseRichieste' => [
                    'required',
                    'integer',
                    'min:1'
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
                'giornate_promoter.array' => 'Le giornate promoter devono essere un array.',
                'giornate_promoter.*.dataGiornata.required' => 'La data è obbligatoria.',
                'giornate_promoter.*.dataGiornata.date' => 'La data non è valida.',
                'giornate_promoter.*.orarioInizioGiornata.required' => 'L\'orario di inizio è obbligatorio.',
                'giornate_promoter.*.orarioInizioGiornata.date_format' => 'L\'orario di inizio non è valido.',
                'giornate_promoter.*.orarioFineGiornata.required' => 'L\'orario di fine è obbligatorio.',
                'giornate_promoter.*.orarioFineGiornata.date_format' => 'L\'orario di fine non è valido.',
                'giornate_promoter.*.minutiTotaliGiornata.required' => 'I minuti totali sono obbligatori.',
                'giornate_promoter.*.minutiTotaliGiornata.integer' => 'I minuti totali devono essere un numero intero.',
                'giornate_promoter.*.minutiTotaliGiornata.min' => 'I minuti totali non possono essere negativi.',
                'giornate_promoter.*.numeroRisorseRichieste.required' => 'Il numero di risorse richieste è obbligatorio.',
                'giornate_promoter.*.numeroRisorseRichieste.integer' => 'Il numero di risorse richieste deve essere un numero intero.',
                'giornate_promoter.*.numeroRisorseRichieste.min' => 'Il numero di risorse richieste non può essere negativo.',
                'giornate_caricamento.array' => 'Le giornate di caricamento devono essere un array.',
                'giornate_caricamento.*.orarioFineGiornata.after' => 'L\'orario di fine deve essere successivo all\'orario di inizio.',
                'giornate_caricamento.*.orarioInizioGiornata.before' => 'L\'orario di inizio deve essere precedente all\'orario di fine.',
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

            // Aggiorna le giornate
            $esigenze = ['promoter', 'caricamento', 'allestimento'];
            \DB::table('giornate')->where('idAdesione', $adesione->id)->delete();
            foreach ($esigenze as $esigenza) {
                $giornateKey = 'giornate_' . $esigenza;
                if ($request->has($giornateKey)) {
                    foreach ($request->input($giornateKey) as $giornata_2) {
                        \DB::table('giornate')->insert([
                            'idAdesione' => $adesione->id,
                            'dataGiornata' => $giornata_2['dataGiornata'] ?? null,
                            'orarioInizioGiornata' => $giornata_2['orarioInizioGiornata'] ?? null,
                            'orarioFineGiornata' => $giornata_2['orarioFineGiornata'] ?? null,
                            'minutiTotaliGiornata' => $giornata_2['minutiTotaliGiornata'] ?? null,
                            'numeroRisorseRichieste' => $giornata_2['numeroRisorseRichieste'] ?? null,
                            'esigenzaGiornata' => $esigenza,
                            'idUtenteCreatoreGiornata' => Auth::user()->id,
                            'dataInserimentoGiornata' => now('Europe/Rome'),
                        ]);
                    }
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