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
        Schema::table('tasks', function(Blueprint $table) {
            $table->timestamp('start_datetime')->default(now());
            $table->timestamp('end_datetime')->default(now()->addHour());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->timestamp('start_time')->default("0");
            $table->timestamp('end_time')->default("0");
            $table->date('task_date')->default("0");
            
            //FIXME: defaults to 0 so tests can re-run the migrations,
            // but it's probably better to just write everything in the task migration
            // ...maybe

            $table->dropColumn('start_datetime');
            $table->dropColumn('end_datetime');
        });
    }
};
