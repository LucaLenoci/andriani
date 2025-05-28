<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adesione;
use App\Models\Evento;
use App\Models\PuntoVendita;
use App\Models\Giornata;
use Carbon\Carbon;

class DatiPerMop extends Controller
{
    public function perTipo(Request $request, $tipo)
    {
        $yesterday = Carbon::now()->subDay()->setTime(3, 0, 0);
        $today = Carbon::now()->setTime(3, 0, 0);

        switch ($tipo) {
            case 'adesioni':
                $result = $this->getAdesioniConCodice();
                break;

            case 'eventi':
                $result = Evento::all();
                break;

            case 'ultime-adesioni':
                $result = Adesione::where(function ($query) use ($yesterday, $today) {
                    $query->whereBetween('dataInserimentoAdesione', [$yesterday, $today])
                          ->orWhereBetween('dataModificaAdesione', [$yesterday, $today]);
                })->get();
                $result = $this->trasformaAdesioniConCodice($result);
                break;

            case 'ultimi-eventi':
                $result = Evento::whereBetween('dataEvento', [$yesterday, $today])->get();
                break;

            case 'ultime-giornate':
                $idAdesione = $request->query('idAdesione');
                if (!$idAdesione) {
                    return response()->json(['error' => 'Parametro idAdesione mancante'], 400);
                }
                $result = Giornata::whereBetween('dataGiornata', [$yesterday, $today])
                    ->where('idAdesione', $idAdesione)
                    ->get();
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

    /**
     * Restituisce tutte le adesioni con codice punto vendita al posto dell'ID.
     */
    private function getAdesioniConCodice()
    {
        $adesioni = Adesione::all();
        return $this->trasformaAdesioniConCodice($adesioni);
    }

    /**
     * Trasforma una collezione di adesioni sostituendo idPuntoVendita con codicePuntoVendita.
     */
    private function trasformaAdesioniConCodice($adesioni)
    {
        return $adesioni->map(function ($item) {
            if ($item->idPuntoVendita) {
                $puntoVendita = PuntoVendita::find($item->idPuntoVendita);
                $item->codicePuntoVendita = $puntoVendita ? $puntoVendita->codicePuntoVendita : null;
                unset($item->idPuntoVendita);
            }
            return $item;
        });
    }
}
