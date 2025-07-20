<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\WatchController;
use App\Models\Article;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [IndexController::class, 'index'])->middleware('guest')->name('homepage');

Route::post('/buy', [IndexController::class, 'buy'])->name('buy');
Route::get('/stripe/webhook', [IndexController::class, 'success'])->name('buy.success');

Route::get('articles/', function () {
    return Inertia::render('Article/Index', [
        'articles' => Article::published()->with('user')->latest()
//            ->paginate(10)
            ->get()->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'excerpt' => $article->excerpt,
                    'body' => $article->body,
                    'author' => [
                        'name' => $article->user->name,
                    ],
                    'seo' => [
                        'title' => $article->seo_title,
                        'description' => $article->meta_description,
                        'keywords' => $article->meta_keywords,
                    ],
                    'link' => $article->link(),
                    'createdAt' => $article->created_at->toIso8601String(),
                ];
            }),
    ]);
})->name('articles');

Route::get('article/{slug}', function () {
    return Inertia::render('Article/Show', [
        'article' => Article::published()->with('user')->where('slug', request()->route('slug'))->firstOrFail()->toResource(),
    ]);
})->name('articles.show');

Route::get('watch', [WatchController::class, 'index'])->name('dashboard');

Route::get('watch/{slug}', [WatchController::class, 'handle'])->name('watch.lesson');

Route::get('/stream/{path}', [StreamController::class, 'handle'])->where('path', '.*')->name('watch.stream');



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
