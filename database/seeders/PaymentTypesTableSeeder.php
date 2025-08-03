<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $types = [
            ['code' => 'payment', 'name' => 'Payment'],
            ['code' => 'refund', 'name' => 'Refund'],
            ['code' => 'chargeback', 'name' => 'Charge Back'],
        ];
        PaymentType::upsert($types, 'code');

    }
}
