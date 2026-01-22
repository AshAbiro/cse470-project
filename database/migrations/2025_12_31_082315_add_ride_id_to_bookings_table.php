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
                $table->foreignId('ticket_type_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('ride_id')->nullable()->constrained()->onDelete('cascade');
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
            // Drop foreign key and column for ticket_type_id
            $table->dropForeign(['ticket_type_id']);
            $table->dropColumn('ticket_type_id');
        });
        Schema::table('bookings', function (Blueprint $table) {
            // Re-add ticket_type_id as nullable foreign key
            $table->foreignId('ticket_type_id')->nullable()->constrained()->onDelete('cascade')->after('user_id');
            // Add ride_id as nullable foreign key
            $table->foreignId('ride_id')->nullable()->after('ticket_type_id')->constrained()->onDelete('cascade');
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
            $table->dropForeign(['ride_id']);
            $table->dropColumn('ride_id');
            $table->dropForeign(['ticket_type_id']);
            $table->dropColumn('ticket_type_id');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('ticket_type_id')->constrained()->onDelete('cascade')->after('user_id');
        });
    }
};
