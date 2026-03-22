<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_items', function (Blueprint $table) {
            $table->id();

            // Referencia al apartado (encabezado)
            $table->foreignId('reservation_id')
                  ->constrained()
                  ->onDelete('cascade'); // Si se elimina el apartado, eliminar sus items

            $table->foreignId('product_id')
                  ->constrained()
                  ->onDelete('restrict');

            $table->integer('quantity')->default(1);

            $table->decimal('unit_price', 10, 2);

            $table->decimal('subtotal', 10, 2);

            $table->timestamps();

            $table->unique(['reservation_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_items');
    }
};
