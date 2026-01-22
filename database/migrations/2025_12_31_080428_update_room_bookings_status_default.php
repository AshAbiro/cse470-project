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

            Schema::rename('room_bookings', 'room_bookings_old');

            Schema::create('room_bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('room_id')->constrained()->onDelete('cascade');
                $table->dateTime('check_in_time');
                $table->dateTime('check_out_time');
                $table->decimal('total_price', 10, 2);
                $table->string('status')->default('pending');
                $table->timestamps();
            });

            DB::table('room_bookings')->insertUsing(
                [
                    'id',
                    'user_id',
                    'room_id',
                    'check_in_time',
                    'check_out_time',
                    'total_price',
                    'status',
                    'created_at',
                    'updated_at',
                ],
                DB::table('room_bookings_old')->select([
                    'id',
                    'user_id',
                    'room_id',
                    'check_in_time',
                    'check_out_time',
                    'total_price',
                    'status',
                    'created_at',
                    'updated_at',
                ])
            );

            Schema::drop('room_bookings_old');

            Schema::enableForeignKeyConstraints();

            return;
        }

        Schema::table('room_bookings', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('room_bookings', function (Blueprint $table) {
            $table->string('status')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            Schema::disableForeignKeyConstraints();

            Schema::rename('room_bookings', 'room_bookings_old');

            Schema::create('room_bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('room_id')->constrained()->onDelete('cascade');
                $table->dateTime('check_in_time');
                $table->dateTime('check_out_time');
                $table->decimal('total_price', 10, 2);
                $table->string('status')->default('booked');
                $table->timestamps();
            });

            DB::table('room_bookings')->insertUsing(
                [
                    'id',
                    'user_id',
                    'room_id',
                    'check_in_time',
                    'check_out_time',
                    'total_price',
                    'status',
                    'created_at',
                    'updated_at',
                ],
                DB::table('room_bookings_old')->select([
                    'id',
                    'user_id',
                    'room_id',
                    'check_in_time',
                    'check_out_time',
                    'total_price',
                    'status',
                    'created_at',
                    'updated_at',
                ])
            );

            Schema::drop('room_bookings_old');

            Schema::enableForeignKeyConstraints();

            return;
        }

        Schema::table('room_bookings', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('room_bookings', function (Blueprint $table) {
            $table->string('status')->default('booked');
        });
    }
};
