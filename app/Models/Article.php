<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [
        'id',
    ];

    public function link(): string
    {

        return route('articles.show', $this->slug);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope the query to only include published articles.
     */
    #[Scope]
    protected function published(Builder $query): void
    {
        $query->withAttributes([
            'status' => 'PUBLISHED',
        ]);
    }
}
