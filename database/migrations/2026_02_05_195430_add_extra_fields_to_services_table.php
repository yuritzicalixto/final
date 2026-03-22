<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Categoría para agrupar servicios en el frontend (ej: "Cabello", "Tratamientos", "Estética & Uñas", "Paquetes Especiales")
            $table->string('category')->nullable()->after('slug');

            // Características del servicio separadas por | (ej: "Análisis profesional de tono|Propuesta personalizada|Plan de mantenimiento")
            $table->text('features')->nullable()->after('description');

            // Etiqueta/badge que aparece sobre la imagen (ej: "Signature", "Popular", "Premium", "Especial")
            $table->string('tag')->nullable()->after('image');

            // Si es un paquete destacado (borde dorado en el frontend)
            $table->boolean('is_highlighted')->default(false)->after('tag');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['category', 'features', 'tag', 'is_highlighted']);
        });
    }
};
