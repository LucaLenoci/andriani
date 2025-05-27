<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Regione extends Model
{
    protected $table = 'regioni';

    protected $fillable = [
        'nomeRegione',
        'idAreaDiCompetenza',
    ];

    public function areaDiCompetenza(): BelongsTo
    {
        return $this->belongsTo(
            AreaDiCompetenza::class,
            'idAreaDiCompetenza'
        );
    }
}