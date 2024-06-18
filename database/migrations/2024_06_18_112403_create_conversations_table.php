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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_1');
            $table->foreign('user_1')->references('id')->on('users')->cascadeOnDelete();

            $table->unsignedBigInteger('user_2');
            $table->foreign('user_2')->references('id')->on('users')->cascadeOnDelete();

            $table->unsignedBigInteger('last_message_id')->nullable();

            $table->string('status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
