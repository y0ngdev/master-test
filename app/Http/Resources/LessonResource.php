<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public static $wrap;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'streamURL' => $this->stream_url,
            'moduleID' => $this->module_id,
            'description' => $this->description,
            'duration' => $this->duration,
            'canWatch' => $this->canWatch($request->user()),
        ];
    }
}
