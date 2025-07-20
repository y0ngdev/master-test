<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Testimonial::query()->insert([
            [
                'name' => 'Alice Johnson',
                'company' => 'TechNova Inc.',
                'content' => 'This platform has revolutionized how we handle internal training. It’s fast, reliable, and beautifully designed.',
                'avatar' => 'https://randomuser.me/api/portraits/women/44.jpg',
                'order' => 1,
                'status' => 'PUBLISHED',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'David Osei',
                'company' => 'Fintech Labs',
                'content' => 'I was impressed by how easy it was to integrate with our existing workflow. Highly recommended!',
                'avatar' => 'https://randomuser.me/api/portraits/men/50.jpg',
                'order' => 2,
                'status' => 'PUBLISHED',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fatima Bello',
                'company' => 'Toddles Ltd.',
                'content' => 'The user experience is top-notch, and the support team is always helpful. Great work!',
                'avatar' => 'https://randomuser.me/api/portraits/women/89.jpg',
                'order' => 3,
                'status' => 'PUBLISHED',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
