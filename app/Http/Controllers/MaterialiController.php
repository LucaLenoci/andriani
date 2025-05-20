<?php

namespace App\Http\Controllers;
use App\Models\Materiale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class PuntiVenditaController extends Controller
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
}
