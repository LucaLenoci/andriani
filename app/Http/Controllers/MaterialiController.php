<?php

namespace App\Http\Controllers;
use App\Models\Materiale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class MaterialiController extends Controller
{
    public function search(Request $request)
    {
        $term = $request->get('term', '');

        $query = Materiale::query();

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('nomeMateriale', 'like', '%' . $term . '%')
                ->orWhere('codiceIdentificativoMateriale', 'like', '%' . $term . '%');
            });
        }

        $puntiVendita = $query->limit(20)->get();

        return response()->json($puntiVendita);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Materiale::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomeMateriale', 'like', '%' . $search . '%')
                  ->orWhere('codiceIdentificativoMateriale', 'like', '%' . $search . '%')
                    ->orWhere('id', $search);
            });
        }

        $materiali = $query->orderBy('id', 'desc')->paginate(10);

        return view('materiali.index', compact('materiali'));
    }
}
