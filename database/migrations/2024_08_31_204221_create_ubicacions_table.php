<?php

use App\Enums\AlturaEnum;
use App\Enums\DensidadEnum;
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
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->id();
            $table->string('latitud');
            $table->string('longitud');
            $table->enum("densidad", DensidadEnum::forMigration());
            $table->enum("altura", AlturaEnum::forMigration());
            $table->unsignedBigInteger('idAmbiente');
            $table->foreign('idAmbiente')->references('id')->on('ambientes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ubicaciones');
    }
};
