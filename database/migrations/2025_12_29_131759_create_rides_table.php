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
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ticket_uid', 8)->unique();
            $table->decimal('price', 8, 2);
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->integer('min_height')->nullable(); // in cm
            $table->integer('thrill_level')->default(1); // 1-5
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
