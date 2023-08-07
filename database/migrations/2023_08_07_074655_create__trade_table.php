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
        Schema::create('trade', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id');
            $table->text('symbol');
            $table->integer('quantity');
            $table->integer('open_price');
            $table->integer('close_price');
            $table->date('open_datetime');
            $table->date('close_datetime');
            $table->boolean('open')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_trade');
    }
};
