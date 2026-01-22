<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            Schema::disableForeignKeyConstraints();

            Schema::rename('rooms', 'rooms_old');

            DB::statement('DROP INDEX IF EXISTS rooms_ticket_uid_unique');
            DB::statement('DROP INDEX IF EXISTS rooms_room_number_unique');

            Schema::create('rooms', function (Blueprint $table) {
                $table->id();
                $table->string('ticket_uid', 8)->unique();
                $table->integer('room_number')->unique();
                $table->integer('floor');
                $table->enum('type', ['standard', 'vip'])->default('standard');
                $table->decimal('price_per_12h', 8, 2);
                $table->text('features');
                $table->enum('status', ['available', 'maintenance', 'out_of_order', 'cleaned', 'unclean', 'repair_required'])
                    ->default('available');
                $table->integer('rating')->default(5);
                $table->string('image_path')->nullable();
                $table->timestamps();
            });

            DB::table('rooms')->insertUsing(
                [
                    'id',
                    'ticket_uid',
                    'room_number',
                    'floor',
                    'type',
                    'price_per_12h',
                    'features',
                    'status',
                    'rating',
                    'image_path',
                    'created_at',
                    'updated_at',
                ],
                DB::table('rooms_old')->select([
                    'id',
                    'ticket_uid',
                    'room_number',
                    'floor',
                    'type',
                    'price_per_12h',
                    'features',
                    'status',
                    'rating',
                    'image_path',
                    'created_at',
                    'updated_at',
                ])
            );

            Schema::drop('rooms_old');

            Schema::enableForeignKeyConstraints();

            return;
        }

        Schema::table('rooms', function (Blueprint $table) {
            $table->enum('status', ['available', 'maintenance', 'out_of_order', 'cleaned', 'unclean', 'repair_required'])->default('available')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            //
        });
    }
};
