<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'paid')) {
                $table->boolean('paid')->default(false)->after('payment_method_id');
            }
            if (!Schema::hasColumn('bookings', 'code')) {
                $table->string('code', 30)->nullable()->after('id');
            }
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['paid', 'code']);
        });
    }
};
