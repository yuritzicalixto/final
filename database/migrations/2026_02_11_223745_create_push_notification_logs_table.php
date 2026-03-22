<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('push_notification_logs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->string('url')->nullable();
            $table->string('type');
            // type puede ser: 'promotional', 'appointment_reminder', 'reservation_reminder'
            $table->enum('audience', ['all', 'selected'])->default('all');
            $table->unsignedInteger('recipients_count')->default(0);
            $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('recipient_ids')->nullable();
            // Guarda los IDs de los usuarios seleccionados (si aplica)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('push_notification_logs');
    }
};
