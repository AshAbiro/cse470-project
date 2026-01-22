<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_uid', 8)->unique();
            $table->integer('room_number')->unique();
            $table->integer('floor');
            $table->enum('type', ['standard', 'vip'])->default('standard');
            $table->decimal('price_per_12h', 8, 2);
            $table->text('features');
            $table->integer('rating')->default(5);
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
