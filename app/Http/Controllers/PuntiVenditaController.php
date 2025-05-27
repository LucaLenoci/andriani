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
        $search = $request->get('search', '');

        $query = PuntoVendita::with('regione');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ragioneSocialePuntoVendita', 'like', '%' . $search . '%')
                  ->orWhere('codicePuntoVendita', 'like', '%' . $search . '%')
                  ->orWhere('insegnaPuntoVendita', 'like', '%' . $search . '%')
                  ->orWhere('indirizzoPuntoVendita', 'like', '%' . $search . '%')
                  ->orWhere('cittaPuntoVendita', 'like', '%' . $search . '%')
                  ->orWhere('provinciaPuntoVendita', 'like', '%' . $search . '%');
            });
        }

        $puntivendita = $query->orderBy('id', 'desc')->paginate(10);

        return view('puntivendita.index', compact('puntivendita'));
    }
}
