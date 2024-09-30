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
        Schema::create('sugestaos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao');
            $table->integer('calorias');
            $table->decimal('proteinas', 8, 2);
            $table->decimal('carboidratos', 8, 2);
            $table->decimal('gorduras', 8, 2);
            $table->string('categoria');
            $table->string('restricoes_para');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sugestaos');
    }
};
