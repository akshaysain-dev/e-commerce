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
        Schema::create('tax_or_shipping_charge', function (Blueprint $table) {
            $table->id();
            $table->string('tax');
            $table->string('shipping_charge');
            $table->string('max_charge_for_shipping');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_or_shipping_charge');
    }
};
