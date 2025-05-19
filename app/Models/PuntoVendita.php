<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class PuntoVendita extends Model
{
    protected $table = 'puntivendita';

    protected $primaryKey = 'id';

    protected $fillable = [
        'codicePuntoVendita',
        'distributorePuntoVendita',
        'insegnaPuntoVendita',
        'ragioneSocialePuntoVendita',
        'indirizzoPuntoVendita',
        'capPuntoVendita',
        'cittaPuntoVendita',
        'provinciaPuntoVendita',
        'idRegionePuntoVendita',
    ];

    public function regione()
    {
        return $this->belongsTo(Regione::class, 'idRegionePuntoVendita');
    }
}