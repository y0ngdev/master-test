<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public static $wrap;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'body' => $this->body,
            'author' => [
                'name' => $this->user->name,
            ],
            'seo' => [
                'title' => $this->seo_title,
                'description' => $this->meta_description,
                'keywords' => $this->meta_keywords,
            ],
            'link' => $this->link(),
            'createdAt' => $this->created_at->toIso8601String(),
        ];
    }
}
