<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('import_batches', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('filename');
            $table->integer('total_rows');
            $table->integer('total_batches');
            $table->integer('processed_batches')->default(0);
            $table->integer('imported_rows')->default(0);
            $table->integer('failed_rows')->default(0);
            $table->enum('status', ['pending', 'processing', 'completed', 'partial', 'failed'])->default('pending');
            $table->text('errors')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_batches');
    }
};
