<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adesione extends Model
{
    use HasFactory;

    protected $table = 'puntiVendita'; 
    public $timestamps = false;

    protected $fillable = [
        'idEvento',
    ];

    protected $casts = [
        'dataInizioAdesione' => 'datetime',
    ];

}