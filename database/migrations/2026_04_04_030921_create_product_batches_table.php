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
        Schema::create('product_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_entry_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->index()->constrained();
            $table->integer('initial_qty')->default(0);
            $table->integer('remaining_qty')->default(0);
            $table->decimal('buy_price', 15, 2)->default(0);
            $table->date('received_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_batches');
    }
};
