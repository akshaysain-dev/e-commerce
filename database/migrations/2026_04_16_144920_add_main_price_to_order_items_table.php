<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {

            // 💰 Main price (original price before margin/discount)
            $table->decimal('main_price', 10, 2)
                  ->nullable()
                  ->after('price'); // adjust position if needed
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('main_price');
        });
    }
};
