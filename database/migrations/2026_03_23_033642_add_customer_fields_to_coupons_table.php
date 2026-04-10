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
        Schema::table('coupons', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('generated_for')->nullable()->after('expires_at');
            $table->unsignedBigInteger('used_by')->nullable()->after('generated_for');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            //
            $table->dropColumn(['generated_for', 'used_by']);
        });
    }
};
