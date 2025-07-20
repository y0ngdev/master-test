<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [
        'id',
    ];

    /**
     * Scope the query to only include published testimonial.
     */
    #[Scope]
    protected function published(Builder $query): void
    {

        $query->withAttributes([
            'status' => 'PUBLISHED',
        ]);
    }
}
