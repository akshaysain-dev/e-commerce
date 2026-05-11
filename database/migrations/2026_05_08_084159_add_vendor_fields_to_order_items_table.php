<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {

            $table->unsignedBigInteger('vendor_id')
                ->nullable()
                ->after('product_id');

            $table->decimal('admin_commission', 12, 2)
                ->default(0)
                ->after('main_price');

            $table->decimal('vendor_amount', 12, 2)
                ->default(0)
                ->after('admin_commission');

            $table->enum('payout_status', [
                'pending',
                'paid',
                'failed'
            ])
            ->default('pending')
            ->after('vendor_amount');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {

            $table->dropColumn([
                'vendor_id',
                'admin_commission',
                'vendor_amount',
                'payout_status',
            ]);
        });
    }
};