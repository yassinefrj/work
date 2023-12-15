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
        Schema::create('group_participations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Add foreign key columns
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('group_id');
            // Add foreign key constraints
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('group_id')->references('id')->on('groups');
            // Add a unique constraint for the composite key
            $table->unique(['user_id', 'group_id']);
            $table->boolean('validated')->default(false);
            $table->enum('status', ['register', 'waiting'])->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_participations');
    }
};
