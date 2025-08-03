<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentType;


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
                PaymentType::upsert($types,'code');

    }
}
