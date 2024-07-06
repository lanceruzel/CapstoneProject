<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->string('name');
            $table->string('address');
            $table->string('postal');
            $table->string('contact');
            $table->string('total');
            $table->string('payment_method');
            $table->string('tracking_number')->nullable();
            $table->string('status');
            $table->string('is_paid')->default(false);

            $table->timestamps();
        });

        // Using DB::statement to alter the table and set the default value
        DB::statement("ALTER TABLE orders MODIFY COLUMN status VARCHAR(255) NOT NULL DEFAULT 'Waiting for seller''s confirmation.'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
