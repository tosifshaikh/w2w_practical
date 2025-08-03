<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample users for testing
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->command->info('✅ Sample user created:');
        $this->command->info('   📧 test@example.com / password: password');
        $this->command->info('');
        $this->command->info('📋 CSV Assignment Ready!');
        $this->command->info('   🔗 Visit http://localhost:8000 to get started');
        $this->command->info('   📄 Sample CSV: /public/sample-transactions.csv');

        $this->call([
            CurrenciesTableSeeder::class,
            PaymentTypesTableSeeder::class,
            PaymentStatusesTableSeeder::class,
        ]);
    }
}
