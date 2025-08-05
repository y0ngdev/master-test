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
