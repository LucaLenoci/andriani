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
        Schema::create('puntiVendita', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('codicePuntoVendita')->unique();
            $table->unsignedBigInteger('distributorePuntoVendita')->nullable();
            $table->string('insegnaPuntoVendita')->nullable();
            $table->string('ragioneSocialePuntoVendita')->nullable();
            $table->string('indirizzoPuntoVendita')->nullable();
            $table->string('capPuntoVendita')->nullable();
            $table->string('cittaPuntoVendita')->nullable();
            $table->string('provinciaPuntoVendita')->nullable();
            $table->unsignedBigInteger('idRegionePuntoVendita')->nullable();

            $table->foreign('idRegionePuntoVendita')
              ->references('id')
              ->on('regioni')
              ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puntiVendita');
    }
};
