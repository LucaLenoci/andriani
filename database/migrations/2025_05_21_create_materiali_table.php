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
        Schema::create('materiali', function (Blueprint $table) {
            $table->id();
            $table->string('nomeMateriale')->nullable();
            $table->string('codiceIdentificativoMateriale')->nullable();
            $table->string('idUtenteCreatoreMateriale')->nullable();
            $table->dateTime('dataInserimentoMateriale')->nullable();
            $table->string('idUtenteModificatoreMateriale')->nullable();
            $table->dateTime('dataModificaMateriale')->nullable();
            $table->string('statoMateriale')->nullable();});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiali');
    }
};
