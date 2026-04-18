<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClockColumnsToAttendanceCorrectRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_correct_requests', function (Blueprint $table) {
            $table->time('clock_in')->nullable()->after('reason');
            $table->time('clock_out')->nullable()->after('clock_in');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_correct_requests', function (Blueprint $table) {
            $table->dropColumn(['clock_in', 'clock_out']);
        });
    }
}
