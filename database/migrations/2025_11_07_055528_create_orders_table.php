<?php

use App\Models\Address;
use App\Models\User;
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
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Address::class)->nullable()->constrained()->nullOnDelete();
            $table->string('status'); // OrderStatus enum
            $table->string('payment_status'); //PaymentStatus enum

            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            $table->string('payment_method'); // PaymentMethodEnum
            $table->string('transaction_id')->nullable(); // session_id
            $table->string('external_reference')->nullable(); // payment_intent_id
            $table->json('payment_metadata')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->decimal('refund_amount', 10, 2)->default(0);
            $table->timestamp('refund_at')->nullable();

            $table->string('rejection_reason')->nullable();
            $table->string('shipping_full_name');
            $table->string('shipping_phone');
            $table->string('shipping_country');
            $table->string('shipping_city');
            $table->string('shipping_street_address');
            $table->timestamps();

            $table->index('transaction_id');
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
