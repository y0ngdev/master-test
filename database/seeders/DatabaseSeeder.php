<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        Only in development environment
//        $this->seedIfEmpty(User::class, UserSeeder::class);

        $this->seedIfEmpty(Plan::class, PlanSeeder::class);
        $this->seedIfEmpty(Article::class, ArticleSeeder::class);
        $this->seedIfEmpty(Testimonial::class, TestimonialSeeder::class);
        $this->seedIfEmpty(Setting::class, SettingsSeeder::class);

    }

    protected function seedIfEmpty(string $model, string $seeder): void
    {
        if ($model::count() === 0) {
            $this->call($seeder);
        } else {
            echo "{$model} table not empty — skipped {$seeder}.\n";
        }
    }
}
