<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stylist_service', function (Blueprint $table) {
            $table->id();

            // Referencia al estilista
            $table->foreignId('stylist_id')
                  ->constrained()
                  ->onDelete('cascade'); // Si se elimina el estilista, eliminar sus asignaciones

            // Referencia al servicio
            $table->foreignId('service_id')
                  ->constrained()
                  ->onDelete('cascade'); // Si se elimina el servicio, eliminar sus asignaciones

            $table->timestamps();

            $table->unique(['stylist_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stylist_service');
    }
};
