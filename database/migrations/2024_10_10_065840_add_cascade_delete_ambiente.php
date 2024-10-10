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
        // Modificar la tabla locales
        Schema::table('locales', function (Blueprint $table) {
            $table->dropForeign(['idAmbiente']);
            $table->foreign('idAmbiente')
                ->references('id')
                ->on('ambientes')
                ->onDelete('cascade'); // Borrar en cascada
        });

        // Modificar la tabla ventanas
        Schema::table('ventanas', function (Blueprint $table) {
            $table->dropForeign(['idAmbiente']);
            $table->foreign('idAmbiente')
                ->references('id')
                ->on('ambientes')
                ->onDelete('cascade'); // Borrar en cascada
        });

        // Modificar la tabla ubicaciones
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->dropForeign(['idAmbiente']);
            $table->foreign('idAmbiente')
                ->references('id')
                ->on('ambientes')
                ->onDelete('cascade'); // Borrar en cascada
        });

        // Modificar la tabla ocupaciones
        Schema::table('ocupaciones', function (Blueprint $table) {
            $table->dropForeign(['idAmbiente']);
            $table->foreign('idAmbiente')
                ->references('id')
                ->on('ambientes')
                ->onDelete('cascade'); // Borrar en cascada
        });
    }

    public function down(): void
    {
        // Revertir las claves foráneas si es necesario (sin 'onDelete')
        Schema::table('locales', function (Blueprint $table) {
            $table->dropForeign(['idAmbiente']);
            $table->foreign('idAmbiente')->references('id')->on('ambientes');
        });

        Schema::table('ventanas', function (Blueprint $table) {
            $table->dropForeign(['idAmbiente']);
            $table->foreign('idAmbiente')->references('id')->on('ambientes');
        });

        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->dropForeign(['idAmbiente']);
            $table->foreign('idAmbiente')->references('id')->on('ambientes');
        });

        Schema::table('ocupaciones', function (Blueprint $table) {
            $table->dropForeign(['idAmbiente']);
            $table->foreign('idAmbiente')->references('id')->on('ambientes');
        });
    }
};
