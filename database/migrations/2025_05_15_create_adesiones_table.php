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
        Schema::create('adesioni', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idEvento');
            $table->unsignedBigInteger('idPuntoVendita');
            $table->date('dataInizioAdesione')->nullable();
            $table->date('dataFineAdesione')->nullable();
            $table->boolean('autorizzazioneExtraBudget')->default(false);
            $table->boolean('richiestaFattibilitaAgenzia')->default(false);
            $table->text('noteAdesione')->nullable();
            $table->string('responsabileCuraAllestimento')->nullable();
            $table->string('statoAdesione')->default('bozza');
            $table->string('idUtenteCreatoreAdesione')->nullable();
            $table->dateTime('dataInserimentoAdesione')->nullable();
            $table->string('idUtenteModificatoreAdesione')->nullable();
            $table->dateTime('dataModificaAdesione')->nullable();
            $table->dateTime('dataInvioAdesione')->nullable();
            $table->string('idUtenteApprovatoreAdesione')->nullable();
            $table->dateTime('dataApprovazioneAdesione')->nullable();
            $table->string('idCorriereAdesione')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adesioni');
    }
};
