<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = ['email', 'customer_name'];

    public function transactions():HasMany
    /**
     * Get all of the comments for the Customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    {
        return $this->hasMany(Transaction::class, 'customer_id', 'id');
       // return $this->hasMany(transactions::class, 'foreign_key', 'local_key');
    }

}
