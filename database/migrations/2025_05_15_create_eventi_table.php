<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('eventi', function (Blueprint $table) {
            $table->id();
            $table->string('nomeEvento')->nullable();
            $table->unsignedBigInteger('annoEvento');
            $table->date('dataInizioEvento')->nullable();
            $table->date('dataFineEvento')->nullable();
            $table->boolean('richiestaPresenzaPromoter')->default(false);
            $table->boolean('previstaAttivitaDiCaricamento')->default(false);
            $table->boolean('previstaAttivitaDiAllestimento')->default(false);
            $table->string('idUtenteCreatoreEvento')->nullable();
            $table->dateTime('dataInserimentoEvento')->nullable();
            $table->string('idUtenteModificatoreEvento')->nullable();
            $table->dateTime('dataModificaEvento')->nullable();
            $table->string('statoEvento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventi');
    }
};
