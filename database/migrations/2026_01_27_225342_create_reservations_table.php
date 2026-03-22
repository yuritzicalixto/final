<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')
                  ->constrained('users')
                  ->onDelete('cascade');


            $table->string('reservation_number')->unique();

            $table->date('reservation_date');

            $table->date('expiration_date');

            $table->decimal('total', 10, 2);

            $table->enum('status', [
                'active',     // Pendiente de recoger y pagar
                'completed',  // Pagado y entregado
                'expired',    // Venció sin recoger (proceso automático)
                'cancelled'   // Cancelado por cliente o admin
            ])->default('active');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['status', 'expiration_date']);
            $table->index('client_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
