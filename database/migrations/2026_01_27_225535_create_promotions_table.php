<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('segment', [
                'all',       // Todos los clientes
                'frequent',  // Clientes frecuentes
                'inactive'   // Clientes inactivos
            ])->default('all');

            $table->enum('send_type', [
                'immediate',  // Enviar ahora
                'scheduled'   // Programar para después
            ])->default('immediate');

            $table->datetime('scheduled_at')->nullable();
            $table->integer('sent_count')->default(0);
            $table->integer('opened_count')->default(0);

            // Estado de la promoción
            $table->enum('status', [
                'draft',     // Borrador, no enviada
                'sent',      // Ya fue enviada
                'scheduled'  // Programada para envío futuro
            ])->default('draft');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
