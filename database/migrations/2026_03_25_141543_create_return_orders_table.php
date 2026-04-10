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
		Schema::create('return_orders', function (Blueprint $table) {
			$table->id();
			$table->foreignId('order_id')->constrained('orders');
			$table->foreignId('customer_id')->constrained('customers');
			$table->text('reason');
			$table->string('status')->default('pending');
			
			$table->string('bank_name')->nullable();
			$table->string('account_holder_name')->nullable();
			$table->string('account_number')->nullable();
			$table->string('ifsc_code')->nullable();
			$table->decimal('refund_amount', 8, 2)->nullable();
			$table->string('stripe_refund_id')->nullable();
			
			$table->timestamps();
		});
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_orders');
    }
};
