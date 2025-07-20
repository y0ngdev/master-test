<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ModuleResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public static $wrap;

    public function toArray(Request $request): array
    {
        //            'lessons' => LessonResource::collection($this->lessons),

        return $this->collection->map(fn ($module): array => [
            'id' => $module->id,
            'title' => $module->title,
            'lessons' => $module->lessons->map(fn ($lesson): array => [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'url' => route('watch.lesson', $lesson->slug),
                'canWatch' => $lesson->canWatch($request->user()),
            ]),
        ])->all();
    }
}
