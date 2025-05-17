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
        Schema::create('regioni', function (Blueprint $table) {
            $table->id();
            $table->string('nomeRegione')->nullable();
            $table->unsignedBigInteger('idAreaDiCompetenza')->nullable();

            $table->foreign('idAreaDiCompetenza')
                ->references('id')
                ->on('areeDiCompetenza')
                ->onDelete('set null');
    });

    // Insert initial data using the query builder
    DB::table('regioni')->insert([
        ['id' => 1, 'nomeRegione' => 'Piemonte', 'idAreaDiCompetenza' => 1],
        ['id' => 2, 'nomeRegione' => "Valle d'Aosta", 'idAreaDiCompetenza' => 1],
        ['id' => 3, 'nomeRegione' => 'Lombardia', 'idAreaDiCompetenza' => 1],
        ['id' => 4, 'nomeRegione' => 'Trentino-Alto Adige', 'idAreaDiCompetenza' => 1],
        ['id' => 5, 'nomeRegione' => 'Veneto', 'idAreaDiCompetenza' => 1],
        ['id' => 6, 'nomeRegione' => 'Friuli Venezia Giulia', 'idAreaDiCompetenza' => 1],
        ['id' => 7, 'nomeRegione' => 'Liguria', 'idAreaDiCompetenza' => 1],
        ['id' => 8, 'nomeRegione' => 'Emilia-Romagna', 'idAreaDiCompetenza' => 1],
        ['id' => 9, 'nomeRegione' => 'Lazio', 'idAreaDiCompetenza' => 2],
        ['id' => 10, 'nomeRegione' => 'Abruzzo', 'idAreaDiCompetenza' => 2],
        ['id' => 11, 'nomeRegione' => 'Molise', 'idAreaDiCompetenza' => 2],
        ['id' => 12, 'nomeRegione' => 'Campania', 'idAreaDiCompetenza' => 2],
        ['id' => 13, 'nomeRegione' => 'Puglia', 'idAreaDiCompetenza' => 2],
        ['id' => 14, 'nomeRegione' => 'Basilicata', 'idAreaDiCompetenza' => 2],
        ['id' => 15, 'nomeRegione' => 'Calabria', 'idAreaDiCompetenza' => 2],
        ['id' => 16, 'nomeRegione' => 'Sicilia', 'idAreaDiCompetenza' => 2],
        ['id' => 17, 'nomeRegione' => 'Umbria', 'idAreaDiCompetenza' => 1],
        ['id' => 18, 'nomeRegione' => 'Marche', 'idAreaDiCompetenza' => 1],
        ['id' => 19, 'nomeRegione' => 'Toscana', 'idAreaDiCompetenza' => 1],
        ['id' => 20, 'nomeRegione' => 'Sardegna', 'idAreaDiCompetenza' => 2],
    ]);
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regioni');
    }
};
