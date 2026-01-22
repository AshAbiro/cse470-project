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
        Schema::table('staff_tasks', function (Blueprint $table) {
            $table->string('priority')->default('normal')->after('status');
            $table->timestamp('due_at')->nullable()->after('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_tasks', function (Blueprint $table) {
            $table->dropColumn(['priority', 'due_at']);
        });
    }
};
