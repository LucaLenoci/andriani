<?php

namespace App\Http\Controllers;
use App\Models\PuntoVendita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class PuntiVenditaController extends Controller
{
    public function search(Request $request)
    {
        $term = $request->get('term', '');

        $query = PuntoVendita::query();

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('ragioneSocialePuntoVendita', 'like', '%' . $term . '%')
                ->orWhere('codicePuntoVendita', 'like', '%' . $term . '%');
            });
        }

        $puntiVendita = $query->limit(20)->get();

        return response()->json($puntiVendita);
    }

    public function index(Request $request)
    {
        $query = PuntoVendita::query()->with('regione');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('insegnaPuntoVendita', 'like', '%' . $request->search . '%')
                ->orWhere('codicePuntoVendita', 'like', '%' . $request->search . '%')
                ->orWhere('cittaPuntoVendita', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('regione')) {
            $query->whereHas('regione', function ($q) use ($request) {
                $q->where('nomeRegione', $request->regione);
            });
        }

        if ($request->filled('provincia')) {
            $query->where('provinciaPuntoVendita', $request->provincia);
        }

        if ($request->filled('citta')) {
            $query->where('cittaPuntoVendita', $request->citta);
        }

        $puntivendita = $query->paginate(10);

        // Popolamento dinamico per filtri a discesa
        $regioni = Regione::orderBy('nomeRegione')->pluck('nomeRegione')->unique();
        $province = PuntoVendita::select('provinciaPuntoVendita')->distinct()->pluck('provinciaPuntoVendita');
        $citta = PuntoVendita::select('cittaPuntoVendita')->distinct()->pluck('cittaPuntoVendita');

        return view('puntivendita.index', compact('puntivendita', 'regioni', 'province', 'citta'));
    }

}
