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

            Schema::rename('bookings', 'bookings_old');

            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('ticket_type_id')->constrained()->onDelete('cascade');
                $table->integer('quantity')->default(1);
                $table->decimal('total_price', 10, 2);
                $table->dateTime('booking_date');
                $table->string('status')->default('pending');
                $table->timestamps();
            });

            DB::table('bookings')->insertUsing(
                [
                    'id',
                    'user_id',
                    'ticket_type_id',
                    'quantity',
                    'total_price',
                    'booking_date',
                    'status',
                    'created_at',
                    'updated_at',
                ],
                DB::table('bookings_old')->select([
                    'id',
                    'user_id',
                    'ticket_type_id',
                    'quantity',
                    'total_price',
                    'booking_date',
                    'status',
                    'created_at',
                    'updated_at',
                ])
            );

            Schema::drop('bookings_old');

            Schema::enableForeignKeyConstraints();

            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('bookings', function (Blueprint $table) {
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

            Schema::rename('bookings', 'bookings_old');

            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('ticket_type_id')->constrained()->onDelete('cascade');
                $table->integer('quantity')->default(1);
                $table->decimal('total_price', 10, 2);
                $table->dateTime('booking_date');
                $table->string('status')->default('confirmed');
                $table->timestamps();
            });

            DB::table('bookings')->insertUsing(
                [
                    'id',
                    'user_id',
                    'ticket_type_id',
                    'quantity',
                    'total_price',
                    'booking_date',
                    'status',
                    'created_at',
                    'updated_at',
                ],
                DB::table('bookings_old')->select([
                    'id',
                    'user_id',
                    'ticket_type_id',
                    'quantity',
                    'total_price',
                    'booking_date',
                    'status',
                    'created_at',
                    'updated_at',
                ])
            );

            Schema::drop('bookings_old');

            Schema::enableForeignKeyConstraints();

            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('status')->default('confirmed');
        });
    }
};
