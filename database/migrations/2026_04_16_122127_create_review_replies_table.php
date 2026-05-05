<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('review_replies', function (Blueprint $table) {
            $table->id();

            // 🔗 Review (Rating) reference
            $table->unsignedBigInteger('rating_id');

            // 👤 Customer OR Admin (flexible)
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();

            // 💬 Message
            $table->text('body');

            // 🏷 Display name (fast rendering)
            $table->string('author_name')->nullable();

            // 🛡 Seller/Admin reply badge
            $table->boolean('is_seller')->default(false);

            $table->timestamps();

            // 🔐 Foreign Keys
            $table->foreign('rating_id')
                ->references('id')
                ->on('ratings')
                ->onDelete('cascade');

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');

            $table->foreign('admin_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_replies');
    }
};