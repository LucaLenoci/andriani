<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adesione extends Model
{
    use HasFactory;

    protected $table = 'adesioni'; 

    protected $fillable = [
        'idEvento',
        'dataInizioAdesione',
        'dataFineAdesione',
        'autorizzazioneExtraBudget',
        'richiestaFattibilitaAgenzia',
        'responsabileCuraAllestimento',
        'statoAdesione',
        'utenteCreatoreAdesione',
        'dataInserimentoAdesione',
        'utenteModificatoreAdesione',
        'dataModificaAdesione',
        'dataInvioAdesione',
        'utenteApprovatoreAdesione',
        'dataApprovazioneAdesione',
        'corriereAdesione',
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
}