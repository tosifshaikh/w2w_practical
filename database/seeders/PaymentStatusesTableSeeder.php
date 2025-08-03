<?php

namespace Database\Seeders;

use App\Models\PaymentStatus;
use Illuminate\Database\Seeder;

class PaymentStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['code' => 'pending', 'name' => 'Pending'],
            ['code' => 'completed', 'name' => 'Completed'],
            ['code' => 'failed', 'name' => 'Failed'],
        ];
        PaymentStatus::upsert($statuses, 'code');
    }
}
