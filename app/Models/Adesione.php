<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adesione extends Model
{
    use HasFactory;

    protected $table = 'adesioni'; 
    public $timestamps = false;

    protected $fillable = [
        'idEvento',
        'idPuntoVendita',
        'dataInizioAdesione',
        'dataFineAdesione',
        'autorizzazioneExtraBudget',
        'richiestaFattibilitaAgenzia',
        'responsabileCuraAllestimento',
        'statoAdesione',
        'idUtenteCreatoreAdesione',
        'dataInserimentoAdesione',
        'idUtenteModificatoreAdesione',
        'dataModificaAdesione',
        'dataInvioAdesione',
        'idUtenteApprovatoreAdesione',
        'dataApprovazioneAdesione',
        'idCorriereAdesione',
        'noteAdesione'
    ];

    protected $casts = [
        'dataInizioAdesione' => 'datetime',
        'dataFineAdesione' => 'datetime',
        'dataInserimentoAdesione' => 'datetime',
        'dataModificaAdesione' => 'datetime',
        'dataInvioAdesione' => 'datetime',
        'dataApprovazioneAdesione' => 'datetime',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'idEvento');
    }

    public function puntoVendita()
    {
        return $this->belongsTo(PuntoVendita::class, 'idPuntoVendita');
    }

    public function utenteCreatore()
    {
        return $this->belongsTo(User::class, 'idUtenteCreatoreAdesione');
    }
}