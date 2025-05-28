<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AreaDiCompetenza extends Model
{
    protected $table = 'areedicompetenza';

    protected $fillable = [
        'nomeArea',
        'idUtenteManagerArea',
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idUtenteManagerArea');
    }
}