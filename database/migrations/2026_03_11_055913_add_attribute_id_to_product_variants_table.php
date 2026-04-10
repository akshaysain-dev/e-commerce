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
        Schema::table('product_variants', function (Blueprint $table) {
            // Adds the column and sets up the foreign key relationship
            $table->foreignId('attribute_id')
                  ->constrained('attributes')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            // 1. Drop the foreign key constraint first
            $table->dropForeign(['attribute_id']);
            
            // 2. Then drop the actual column
            $table->dropColumn('attribute_id');
        });
    } 
};
