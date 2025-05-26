<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adesione;
use App\Models\Evento;
use App\Models\PuntoVendita;

class DatiPerMop extends Controller
{
    public function perTipo(Request $request, $tipo)
    {
        switch ($tipo) {
            case 'adesioni':
                $result = Adesione::all();
                $result->transform(function ($item) {
                    if (isset($item->idPuntoVendita)) {
                        $puntoVendita = PuntoVendita::find($item->idPuntoVendita);
                        $item->codicePuntoVendita = $puntoVendita ? $puntoVendita->codicePuntoVendita : null;
                        unset($item->idPuntoVendita);
                    }
                    return $item;
                });
                break;

            case 'eventi':
                $result = Evento::all();
                break;

            case 'ultima-adesione':
                $result = Adesione::orderBy('created_at', 'desc')->first();
                break;

            case 'giornate':
                $idAdesione = $request->query('idAdesione');
                if (!$idAdesione) {
                    return response()->json(['error' => 'Parametro idAdesione mancante'], 400);
                }
                $result = Giornata::where('idAdesione', $idAdesione)->get();
                break;

            default:
                return response()->json(['error' => 'Tipo non valido'], 400);
        }

        return response()->json($result);
    }

}
