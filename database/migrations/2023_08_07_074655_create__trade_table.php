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
        Schema::create('trades', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('profile_id')->onDelete('cascade');
            $table->text('symbol');
            $table->integer('quantity');
            $table->integer('open_price');
            $table->integer('close_price')->nullable();
            $table->date('open_datetime');
            $table->date('close_datetime')->nullable();
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
