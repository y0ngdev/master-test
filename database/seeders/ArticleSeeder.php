<?php

namespace Database\Seeders;

use App\Models\Article;
use DB;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //        ToDO
        DB::table('modules')->insert([
            [
                'id' => 1,
                'title' => 'Getting Started',
                'order' => 1,
                'status' => 'PUBLISHED',
                'created_at' => '2025-06-30 10:27:11',
                'updated_at' => '2025-06-30 10:27:11',
            ],
            [
                'id' => 2,
                'title' => 'Wrapping Up',
                'order' => 2,
                'status' => 'PUBLISHED',
                'created_at' => '2025-06-30 10:28:25',
                'updated_at' => '2025-06-30 10:28:25',
            ],
        ]);
        DB::table('lessons')->insert([
            [
                'id' => '0197bdbd-da23-71ee-b462-2fc3a56f36cb',
                'title' => 'Make an Alias',
                'slug' => 'make-an-alias',
                'description' => <<<'MARKDOWN'
# Summary
Learn how to interpret query plan costs, including startup and total costs, to optimize database performance. Discover how rows and width metrics reduce unnecessary data handling for faster queries.

# Video Transcript
For each node on the query plan, there is a set of parentheses and then a bunch of information that’s really hard to decipher...
[Truncated for brevity — full content goes here]
MARKDOWN,
                'duration' => 282,
                'can_preview' => true,
                'position' => 1,
                'status' => 'READY',
                'video_driver' => 'FILE',
                'disk' => null,
                'video_source' => 'lessons/Getting Started/Make an Alias/0197bdbd-da23-71ee-b462-2fc3a56f36cb.m3u8',
                'is_published' => true,
                'module_id' => 1,
                'created_at' => '2025-06-29 22:10:25',
                'updated_at' => '2025-06-30 10:42:22',
            ],
            [
                'id' => '0197be1c-e35e-7310-9789-04da722133c7',
                'title' => 'Options for Reducing Inertia Payloads',
                'slug' => 'options-for-reducing-inertia-payloads',
                'description' => 'aaaaaaaaaaaaaaaaaaaaaaa',
                'duration' => 882,
                'can_preview' => false,
                'position' => 3,
                'status' => 'READY',
                'video_driver' => 'FILE',
                'disk' => null,
                'video_source' => 'lessons/Wrapping/Options for Reducing Inertia Payloads/0197be1c-e35e-7310-9789-04da722133c7.m3u8',
                'is_published' => true,
                'module_id' => 2,
                'created_at' => '2025-06-29 23:54:13',
                'updated_at' => '2025-06-30 00:09:57',
            ],
        ]);

        Article::query()->insert([
            [
                'author_id' => 1,
                'title' => 'Demystifying Event-Driven Architecture in Laravel',
                'seo_title' => 'Event-Driven Architecture in Laravel',
                'excerpt' => 'Learn how to decouple your Laravel application using events, listeners, and subscribers.',
                'body' => <<<'MD'
# Demystifying Event-Driven Architecture in Laravel

Event-driven architecture (EDA) helps decouple your Laravel application by separating concerns using events and listeners.

## What is Event-Driven Architecture?

In simple terms, EDA allows components to react to changes or "events" in your application without tightly coupling the logic.

## Example: Sending a Welcome Email

```php
// Event
class UserRegistered {
    public function __construct(public User $user) {}
}

// Listener
class SendWelcomeEmail {
    public function handle(UserRegistered $event) {
        Mail::to($event->user->email)->send(new WelcomeMail());
    }
}
````

Register both in `EventServiceProvider`, and you're good to go.
MD,
                'slug' => 'event-driven-architecture-laravel',
                'meta_description' => 'Explore event-driven architecture in Laravel with real-world examples.',
                'meta_keywords' => 'laravel, events, architecture, design patterns',
                'status' => 'PUBLISHED',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'author_id' => 1,
                'title' => 'A Practical Guide to Writing Clean PHP Code',
                'seo_title' => 'Writing Clean PHP Code',
                'excerpt' => 'This guide shares tips, examples, and refactoring patterns to help you write clean, maintainable PHP code.',
                'body' => <<<'MD'
```

# A Practical Guide to Writing Clean PHP Code

Writing clean code isn’t just about formatting — it’s about clarity, maintainability, and expressing intent .

## 1. Name Things Clearly

Bad:

```php
$a = 5;
$b = $a * 24;
```

Better:

```php
$hoursPerDay = 24;
$days = 5;
$totalHours = $days * $hoursPerDay;
```

## 2. Keep Functions Small

break down long functions into small, single - purpose methods .

## 3. Avoid Deep Nesting

use early returns to simplify complex `if` / `else` blocks .
MD,
                'slug' => 'clean-php-code-guide',
                'meta_description' => 'Learn how to write clean, readable PHP code with this practical guide.',
                'meta_keywords' => 'php, clean code, best practices, refactoring',
                'status' => 'PUBLISHED',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'author_id' => 1,
                'title' => 'Building a Markdown Blog with Laravel and Tailwind CSS',
                'seo_title' => 'Markdown Blog Laravel + Tailwind',
                'excerpt' => 'See how to build a modern developer blog using Markdown, Laravel, and Tailwind.',
                'body' => <<<MD
```

# Building a Markdown Blog with Laravel and Tailwind CSS

Want to create a minimal blog that feels like writing in VS Code ? This guide is for you .

## Key Stack

* Laravel for backend
              * Tailwind CSS for styling
                                 * Parsedown or Laravel Markdown for rendering

## Setup

```bash
composer require league/commonmark
npm install tailwindcss
```

use the Markdown parser in your controller:

```php
use League\CommonMark\CommonMarkConverter;

\$converter = new CommonMarkConverter();
\$html = \$converter->convertToHtml(\$markdownContent);
```

MD,
                'slug' => 'markdown-blog-laravel-tailwind',
                'meta_description' => 'Build a modern developer-friendly Markdown blog using Laravel and Tailwind CSS.',
                'meta_keywords' => 'markdown, laravel, tailwindcss, developer blog',
                'status' => 'DRAFT',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
