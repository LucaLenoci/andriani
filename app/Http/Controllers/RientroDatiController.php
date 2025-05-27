<?php

namespace App\Http\Controllers;
use App\Models\Evento;
use App\Models\PuntoVendita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Http;

class EventiController extends Controller
{
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

            $regioni = \DB::table('regioni')->get();

            //$url = "https://field.promomedia.dev/api/apiTest.php?table=valori_rd_evento&idEvento=" . $evento->id;
            
            // Hardcoded URL
            $url = "https://field.promomedia.dev/api/apiTest.php?table=valori_rd_evento&idEvento=558";


            $response = Http::withoutVerifying()->get($url);

            if ($response->successful()) {
                $datiPassaggi = $response->json();
            } else {
                $datiPassaggi = [];
            }

            return view('rientrodati.show', compact('evento', 'puntiVendita', 'datiPassaggi'));
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento dell\'evento: ' . $e->getMessage());
            return redirect()->route('eventi.index')->withInput()->withErrors(['error' => 'Errore durante il caricamento dell\'evento: ' . $e->getMessage()]);
        }
    }
}