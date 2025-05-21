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
        Schema::create('adesionemateriali', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idAdesione')->nullable();
            $table->unsignedBigInteger('idMateriale')->nullable();
            $table->unsignedBigInteger('quantitaRichiesta')->nullable();

            $table->foreign('idAdesione')
                ->references('id')
                ->on('adesioni')
                ->onDelete('cascade');
            
            $table->foreign('idMateriale')
                ->references('id')
                ->on('materiali')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adesionemateriali');
    }
};
