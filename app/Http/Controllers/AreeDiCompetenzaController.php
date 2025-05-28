<?php

namespace App\Http\Controllers;

use App\Models\AreaDiCompetenza;
use Illuminate\Http\Request;

class AreeDiCompetenzaController extends Controller
{
    public function index()
    {
        // Recupera le aree di competenza con il manager associato
        $aree = AreaDiCompetenza::with('manager')->get();

        // Suddivide le regioni per area (NORD/SUD)
        $nord = $aree->where('nomeArea', 'NORD')->first();
        $sud = $aree->where('nomeArea', 'SUD')->first();

        // Recupera i responsabili e le regioni associate
        $nordResponsabile = $nord && $nord->manager ? $nord->manager->name : 'Non assegnato';
        $sudResponsabile = $sud && $sud->manager ? $sud->manager->name : 'Non assegnato';

        // Supponiamo che tu abbia una relazione o un campo che elenca le regioni per area
        // Qui usiamo un array statico come esempio
        $nordRegioni = ['Piemonte', 'Lombardia', 'Veneto', 'Liguria', 'Emilia-Romagna', 'Friuli-Venezia Giulia', 'Trentino-Alto Adige', 'Valle d\'Aosta'];
        $sudRegioni = ['Abruzzo', 'Molise', 'Campania', 'Puglia', 'Basilicata', 'Calabria', 'Sicilia', 'Sardegna'];

        return view('areedicompetenza.index', compact(
            'nordResponsabile',
            'sudResponsabile',
            'nordRegioni',
            'sudRegioni'
        ));
    }
}