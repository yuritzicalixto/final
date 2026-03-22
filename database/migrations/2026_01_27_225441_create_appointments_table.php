<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('stylist_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null');

            $table->foreignId('service_id')
                  ->constrained()
                  ->onDelete('restrict');

            $table->foreignId('reservation_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null');

            $table->date('date');

            $table->time('start_time');

            $table->time('end_time');

            $table->enum('status', [
                'pending',    // Recién creada, esperando confirmación
                'confirmed',  // Confirmada por admin o automáticamente
                'completed',  // El servicio ya se realizó
                'cancelled',  // Fue cancelada
                'no_show'     // El cliente no se presentó
            ])->default('pending');

            $table->text('notes')->nullable();

            $table->text('cancellation_reason')->nullable();

            $table->enum('cancelled_by', ['client', 'admin'])->nullable();

            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();

            $table->index(['date', 'stylist_id', 'status']);

            $table->index(['client_id', 'status']);

            $table->index(['date', 'start_time', 'end_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
