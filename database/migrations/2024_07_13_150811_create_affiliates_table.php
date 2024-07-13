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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('users')->cascadeOnDelete();

            $table->unsignedBigInteger('promoter_id');
            $table->foreign('promoter_id')->references('id')->on('users')->cascadeOnDelete();

            $table->string('affiliate_code');
            $table->float('rate');
            $table->float('totalCommissioned')->default(0);
            $table->string('status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
