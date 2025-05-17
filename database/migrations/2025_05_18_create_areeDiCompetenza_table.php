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
        Schema::create('areeDiCompetenza', function (Blueprint $table) {
            $table->id();
            $table->string('nomeArea')->nullable();
            $table->unsignedBigInteger('idUtenteManagerArea')->nullable();

            $table->foreign('idUtenteManagerArea')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });

        DB::table('areeDiCompetenza')->insert([
        ['id' => 1, 'nomeArea' => 'NORD', 'idUtenteManagerArea' => 1],
        ['id' => 2, 'nomeArea' => "SUD", 'idUtenteManagerArea' => 1]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areeDiCompetenza');
    }
};
