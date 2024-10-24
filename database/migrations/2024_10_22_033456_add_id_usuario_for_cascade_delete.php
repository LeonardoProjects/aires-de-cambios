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
        // Modificar la clave foránea para agregar "cascade on delete"
        Schema::table('ambientes', function (Blueprint $table) {
            // Eliminar la clave foránea actual
            $table->dropForeign(['idUsuario']);

            // Volver a agregar la clave foránea con "onDelete('cascade')"
            $table->foreign('idUsuario')
                ->references('id')->on('users')
                ->onDelete('cascade'); // Agregar la opción de eliminación en cascada
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    { // Revertir la clave foránea a su estado original sin cascade
        Schema::table('ambientes', function (Blueprint $table) { // Eliminar la clave foránea actual con "cascade"
            $table->dropForeign(['idUsuario']);
            // Volver a agregar la clave foránea sin "onDelete('cascade')"
            $table->foreign('idUsuario')->references('id')->on('users');
        });
    }
};
