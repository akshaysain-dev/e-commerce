<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /* Schema::table('ratings', function (Blueprint $table) {

            $table->string('title', 100)
                  ->nullable()
                  ->after('review');

            $table->unsignedInteger('helpful_yes')
                  ->default(0)
                  ->after('title');

            $table->unsignedInteger('helpful_no')
                  ->default(0)
                  ->after('helpful_yes');

            $table->boolean('is_verified_purchase')
                  ->default(false)
                  ->after('helpful_no');

            // Ek customer ek product ko sirf ek baar review kare
            $table->unique(['customer_id', 'product_id'], 'unique_customer_product');
        }); */
    }

    public function down(): void
    {
       /*  Schema::table('ratings', function (Blueprint $table) {
            $table->dropUnique('unique_customer_product');
            $table->dropColumn([
                'title',
                'helpful_yes',
                'helpful_no',
                'is_verified_purchase',
            ]);
        }); */
    }
};