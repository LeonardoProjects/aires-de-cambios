<?php

use App\Enums\CalidadEnum;
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
        Schema::create('ventanas', function (Blueprint $table) {
            $table->id();
            $table->double('largo');
            $table->double('alto');
            $table->boolean('corrediza')->default(true);
            $table->enum("calidad", CalidadEnum::forMigration());
            $table->unsignedBigInteger('idAmbiente');
            $table->foreign('idAmbiente')->references('id')->on('ambientes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventanas');
    }
};
