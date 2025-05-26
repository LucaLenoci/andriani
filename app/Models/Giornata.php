<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Adesione;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Giornata extends Model
{
    protected $table = 'giornate';

    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'idAdesione',
        'esigenzaGiornata',
        'dataGiornata',
        'orarioInizioGiornata',
        'orarioFineGiornata',
        'minutiTotaliGiornata',
        'numeroRisorseRichieste',
        'idUtenteCreatoreGiornata',
        'dataInserimentoGiornata',
        'idUtenteModificatoreGiornata',
        'dataModificaGiornata',
    ];

    public function adesione()
    {
        return $this->belongsTo(Adesione::class, 'idAdesione');
    }
}
