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
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->decimal('amount', 10, 2);
            $table->unsignedInteger('last4');
            $table->unsignedInteger('currency_id');
            $table->unsignedInteger('payment_type_id');
            $table->unsignedInteger('payment_status_id');
            $table->dateTime('transaction_date')->nullable();
            $table->timestamps();

            $table->index('customer_id');
            $table->index('payment_type_id');
            $table->index('payment_status_id');
            $table->index('currency_id');
            $table->index('transaction_date');

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
