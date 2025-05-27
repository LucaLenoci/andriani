<?php

namespace App\Http\Controllers;
use App\Models\Evento;
use App\Models\PuntoVendita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;


class RientroDatiController extends Controller
{
    public function show(Request $request, $id)
    {
        try {
            $evento = Evento::findOrFail($id);

            $puntiVendita = \DB::table('eventopuntivendita')
                ->join('puntivendita', 'eventopuntivendita.idPuntoVendita', '=', 'puntivendita.id')
                ->where('eventopuntivendita.idEvento', $id)
                ->get();

            $materiali = \DB::table('eventomateriali')
                ->join('materiali', 'eventomateriali.idMateriale', '=', 'materiali.id')
                ->where('eventomateriali.idEvento', $id)
                ->get();

            $regioni = \DB::table('regioni')->get();

            //$url = "https://field.promomedia.dev/api/apiTest.php?table=valori_rd_evento&idEvento=" . $evento->id;

            // Hardcoded URL
            $url = "https://field.promomedia.dev/api/apiTest.php?table=valori_rd_evento&idEvento=558";

            $response = Http::withoutVerifying()->get($url);
            $datiPassaggiRaw = $response->successful() ? $response->json() : [];

            // Filtro
            $filtroUtente = $request->input('filtro_utente');
            $filtroProdotto = $request->input('filtro_prodotto');
            $filtroPdv = $request->input('filtro_pdv');
                        
            // Prepara dati filtrati
            $gruppi = [];

            foreach ($datiPassaggiRaw as $passaggiUtente) {
                if (empty($passaggiUtente)) continue;

                $utenteId = $passaggiUtente[0]['idUtente'] ?? null;

                if ($filtroUtente && $utenteId != $filtroUtente) continue;

                $passaggiPerProdotto = [];

                foreach ($passaggiUtente as $passaggio) {
                    $prodotto = $passaggio['prodotto'] ?? 'Prodotto non specificato';

                    if ($filtroProdotto && $prodotto != $filtroProdotto) continue;
                    if ($filtroPdv && ($passaggio['pdv']['Codice'] ?? null) != $filtroPdv) continue;

                    $passaggiPerProdotto[$prodotto][] = $passaggio;
                }

                if (!empty($passaggiPerProdotto)) {
                    $gruppi[] = [
                        'utenteId' => $utenteId,
                        'utenteNome' => $passaggiUtente[0]['nomeUtente'] ?? 'Utente sconosciuto',
                        'pdv' => $passaggiUtente[0]['pdv'] ?? [],
                        'idPassaggio' => $passaggiUtente[0]['idPassaggio'] ?? null,
                        'prodotti' => $passaggiPerProdotto,
                    ];
                }
            }

            // Pagina e imposta i risultati
            $paginaCorrente = LengthAwarePaginator::resolveCurrentPage();
            $perPagina = 5;
            $gruppiPaginati = new LengthAwarePaginator(
                array_slice($gruppi, ($paginaCorrente - 1) * $perPagina, $perPagina),
                count($gruppi),
                $perPagina,
                $paginaCorrente,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            // Estrai valori unici per i filtri
            $utentiUnici = [];
            $prodottiUnici = [];
            $pdvUnici = [];

            foreach ($datiPassaggiRaw as $passaggiUtente) {
                if (empty($passaggiUtente)) continue;

                $utente = $passaggiUtente[0];
                $utenteId = $utente['idUtente'] ?? '';
                $utenteNome = $utente['nomeUtente'] ?? 'Utente sconosciuto';
                $utentiUnici[$utenteId] = $utenteNome;

                foreach ($passaggiUtente as $passaggio) {
                    $prodotto = $passaggio['prodotto'] ?? 'Prodotto non specificato';
                    $prodottiUnici[$prodotto] = true;

                    $pdv = $passaggio['pdv'] ?? [];
                    if (isset($pdv['Codice'])) {
                        $codice = $pdv['Codice'];
                        $ragione = $pdv['RagioneSociale'] ?? '';
                        $pdvUnici[$codice] = $codice . ($ragione ? ' - ' . $ragione : '');
                    }
                }
            }

            return view('rientrodati.show', compact(
                'evento',
                'puntiVendita',
                'materiali',
                'regioni',
                'gruppiPaginati',
                'utentiUnici',
                'prodottiUnici',
                'pdvUnici',
                'filtroUtente',
                'filtroProdotto',
                'filtroPdv'
            ));
        } catch (Exception $e) {
            \Log::error('Errore durante il caricamento dell\'evento: ' . $e->getMessage());
            return redirect()->route('eventi.index')->withInput()->withErrors(['error' => 'Errore durante il caricamento dell\'evento: ' . $e->getMessage()]);
        }
    }

}