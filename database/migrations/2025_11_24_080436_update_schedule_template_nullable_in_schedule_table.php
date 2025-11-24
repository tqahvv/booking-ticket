<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {

            if (!Schema::hasColumn('schedules', 'schedule_template_id')) {
                $table->unsignedBigInteger('schedule_template_id')->nullable()->after('id');
                $table->foreign('schedule_template_id')
                    ->references('id')
                    ->on('schedule_templates')
                    ->onDelete('set null');
            }

            if (!Schema::hasColumn('schedules', 'locked_until')) {
                $table->dateTime('locked_until')->nullable()->after('seats_available');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (Schema::hasColumn('schedules', 'schedule_template_id')) {
                $table->dropForeign(['schedule_template_id']);
                $table->dropColumn('schedule_template_id');
            }

            if (Schema::hasColumn('schedules', 'locked_until')) {
                $table->dropColumn('locked_until');
            }
        });
    }
};
