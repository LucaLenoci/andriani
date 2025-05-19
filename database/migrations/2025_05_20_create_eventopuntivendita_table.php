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
        Schema::create('eventopuntivendita', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idEvento')->nullable();
            $table->unsignedBigInteger('idPuntoVendita')->nullable();

            $table->foreign('idEvento')
                ->references('id')
                ->on('eventi')
                ->onDelete('cascade');
            
            $table->foreign('idPuntoVendita')
                ->references('id')
                ->on('puntiVendita')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventopuntivendita');
    }
};
