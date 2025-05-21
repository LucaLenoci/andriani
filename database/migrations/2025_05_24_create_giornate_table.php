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
        Schema::create('giornate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idAdesione')->nullable();
            $table->string('esigenzaGiornata')->nullable();
            $table->date('dataInizioGiornata')->nullable();
            $table->date('dataFineGiornata')->nullable();
            $table->time('oraInizioGiornata')->nullable();
            $table->time('oraFineGiornata')->nullable();
            $table->integer('minutiTotaliGiornata')->nullable();
            $table->integer('numeroRisorseRichieste')->nullable();
            $table->string('idUtenteCreatoreGiornata')->nullable();
            $table->dateTime('dataInserimentoGiornata')->nullable();
            $table->string('idUtenteModificatoreGiornata')->nullable();
            $table->dateTime('dataModificaGiornata')->nullable();

            $table->foreign('idAdesione')
                ->references('id')
                ->on('adesioni')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('giornate');
    }
};
