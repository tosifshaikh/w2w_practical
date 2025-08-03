<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentStatus;
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
        PaymentStatus::upsert($statuses,'code');
    }
}
