<?php

namespace App\Filament\Admin\Resources\LessonResource\Pages;

use App\Filament\Admin\Resources\LessonResource;
use App\Jobs\ConvertVideoForStreaming;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

// use App\Jobs\UploadLessonVideoToVimeo;

class CreateLesson extends CreateRecord
{
    protected static string $resource = LessonResource::class;

    protected function handleRecordCreation(array $data): Model
    {

        return $this->getModel()::create([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'description' => $data['description'],
            'duration' => $data['duration'] ?? 0,
            'can_preview' => $data['can_preview'],
            'position' => $data['position'],
            'video_source' => $data['video'],
            'is_published' => $data['is_published'],
            'module_id' => $data['module_id'],

        ]);
    }

    protected function afterCreate(): void
    {

        if (config('video.driver') === 'file_system') {
            ConvertVideoForStreaming::dispatch($this->getRecord());
        }

        //        else {
        //                UploadLessonVideoToVimeo::dispatch(
        //                    $record->id,
        //                    $videoPath,
        //                    $record->title,
        //                    $record->description ?? ''
        //                );

        //        }

    }

    protected function getRedirectUrl(): string
    {
        return self::getResource()::getUrl('index');
    }
}
