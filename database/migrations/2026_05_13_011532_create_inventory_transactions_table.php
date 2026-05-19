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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->date('transaction_date');
            $table->enum('type', ['IN', 'OUT']);
            $table->enum('status', ['GOOD', 'DAMAGED', 'EXPIRED', 'MISSING']);
            $table->enum('approve_status', ['APPROVED', 'PENDING', 'REJECTED'])->default('PENDING');
            $table->integer('quantity');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('approved_note')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('type');
            $table->index('status');
            $table->index('approve_status');
            $table->index('transaction_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
