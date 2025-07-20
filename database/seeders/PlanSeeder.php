<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::query()->create([
            'name' => 'Complete Course',
            'sort_order' => '1',
            'short_description' => 'Perfect for independent creators and instructors',
            'features' => [
                '117 in-depth video tutorials',
                'Over 16 hours of content',
                'Advanced topics like JSON, Vectors, and more',
                'Lifetime access',
                'Optimize Postgres for production workloads',
                'Understand database indexes at a deep level',
            ],
            'price' => '289',
            'gateway_meta' => [
                'stripe' => 'price_abc123',
            ],
            'is_active' => true,
            'is_featured' => true,
        ]);
    }
}
