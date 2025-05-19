<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'eventi'; 
    public $timestamps = false;


    protected $fillable = [
        'nomeEvento',
        'annoEvento',
        'dataInizioEvento',
        'dataFineEvento',
        'richiestaPresenzaPromoter',
        'previstaAttivitaDiCaricamento',
        'previstaAttivitaDiAllestimento',
        'idUtenteCreatoreEvento',
        'dataInserimentoEvento',
        'idUtenteModificatoreEvento',
        'dataModificaEvento',
        'statoEvento'
    ];

    protected $casts = [
        'dataInizioEvento' => 'datetime',
        'dataFineEvento' => 'datetime',
        'dataInserimentoEvento' => 'datetime',
        'dataModificaEvento' => 'datetime'
    ];

    public function utenteCreatore()
    {
        return $this->belongsTo(User::class, 'idUtenteCreatoreEvento');
    }

    public function puntiVendita()
{
    return $this->belongsToMany(
        PuntoVendita::class,
        'eventipuntivendita',
        'idEvento',      // Foreign key su eventipuntivendita per Evento
        'idPuntoVendita' // Foreign key su eventipuntivendita per PuntoVendita
    );
}
}