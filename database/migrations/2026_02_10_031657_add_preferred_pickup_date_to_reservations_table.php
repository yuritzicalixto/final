<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Fecha preferida de recolección (opcional, el cliente la elige al confirmar)
            $table->dateTime('preferred_pickup_date')->nullable()->after('expiration_date');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('preferred_pickup_date');
        });
    }
};
