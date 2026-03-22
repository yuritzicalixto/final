<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stylists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->string('address')->nullable();

            $table->string('specialties')->nullable();

            $table->string('photo')->nullable();

            $table->string('phone', 15)->nullable();

            $table->enum('status', ['available', 'unavailable'])->default('available');

            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stylists');
    }
};
