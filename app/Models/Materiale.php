<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class PuntoVendita extends Model
{
    protected $table = 'materiali';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nomeMateriale',
        'codiceIdentificativoMateriale',
    ];
}