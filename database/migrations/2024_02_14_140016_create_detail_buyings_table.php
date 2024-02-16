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
        Schema::create('detail_buyings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('PenjualanID');
            $table->unsignedBigInteger('ProdukID');
            $table->integer('JumlahProduk');
            $table->bigInteger('Subtotal');
            $table->timestamps();
            $table->foreign('PenjualanID')->references('id')->on('buyings');
            $table->foreign('ProdukID')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_buyings');
    }
};
