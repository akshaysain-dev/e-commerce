<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Existing invalid values fix karo pehle
        DB::statement("UPDATE orders SET status = 'pending' WHERE status = 'on the way'");
        DB::statement("UPDATE orders SET status = 'delivered' WHERE status = 'completed'");

        // Step 2: ENUM update karo
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending'");

        // Step 3: Naye columns add karo
        Schema::table('orders', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->after('payment_method');
            $table->string('card_last_four', 4)->nullable()->after('transaction_id');
            $table->string('card_holder_name')->nullable()->after('card_last_four');
        });
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'on the way', 'completed', 'cancelled') DEFAULT 'pending'");

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'card_last_four', 'card_holder_name']);
        });
    }
};