<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: function (?string $value) {

                if (Str::startsWith($value, ['http://', 'https://', 'data:image'])) {
                    return $value;
                }

                return Storage::disk('public')->url($value);
            }
        );
    }

}
