<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            if (Schema::hasColumn('bookings', 'time')) {
                $table->dropColumn('time');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->time('time')->nullable();

            $table->dropColumn(['start_time', 'end_time']);
        });
    }
};

