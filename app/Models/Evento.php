<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'eventi'; 

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
        'dataModificaEvento'
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
}