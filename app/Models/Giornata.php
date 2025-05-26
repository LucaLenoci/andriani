<?php

namespace App\Models;


class Giornata extends Model
{
    protected $table = 'giornate';

    protected $primaryKey = 'id';

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
