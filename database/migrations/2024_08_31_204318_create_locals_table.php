<?php

use App\Enums\TipoHabEnum;
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
        Schema::create('locales', function (Blueprint $table) {
            $table->id();
            $table->double('largo');
            $table->double('ancho');
            $table->double('alto');
            $table->enum("tipoHabitacion", TipoHabEnum::forMigration());
            $table->unsignedBigInteger('idAmbiente');
            $table->foreign('idAmbiente')->references('id')->on('ambientes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locales');
    }
};
