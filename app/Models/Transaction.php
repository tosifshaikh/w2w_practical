<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Transaction extends Model
{
    protected $fillable = ['customer_id', 'amount', 'last4', 'currency_id', 'payment_type_id', 'payment_status_id', 'transaction_date'];

    protected $casts = [
        'transaction_date' => 'datetime:Y-m-d H:i:s',
    ];

    protected function formattedTransactionDate(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->transaction_date) {
                    return null;
                }

                // Ensure we have a Carbon instance
                $date = $this->transaction_date instanceof Carbon
                    ? $this->transaction_date
                    : Carbon::parse($this->transaction_date);

                return $date->format('M d, Y h:i A');
            }
        );
    }

    /**
     * Get the customer that owns the Transaction
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Get the currency that owns the Transaction
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    /**
     * Get the payment type that owns the Transaction
     */
    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    /**
     * Get the payment status that owns the Transaction
     */
    public function paymentStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }
}
