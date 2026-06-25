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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', [
                'pending',
                'processing',
                'paid',
                'shipped',
                'delivered',
                'cancelled',
                'refunded',
            ])->default('pending');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->json('shipping_address')->nullable();
            $table->json('billing_address')->nullable();
            $table->string('shipping_method', 100)->nullable();
            $table->string('tracking_number', 255)->nullable();
            $table->string('courier', 100)->nullable();
            $table->string('payment_method', 100)->nullable();
            $table->enum('payment_status', [
                'unpaid',
                'paid',
                'failed',
                'expired',
                'refunded',
            ])->default('unpaid');
            $table->string('payment_token', 255)->nullable();
            $table->text('payment_url')->nullable();
            $table->timestamp('payment_expired_at')->nullable();
            $table->string('voucher_code', 50)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('ordered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['order_number', 'user_id', 'status', 'payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
