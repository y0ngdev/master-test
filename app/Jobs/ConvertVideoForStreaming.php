<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Lesson;
use Exception;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 5;
    public int $timeout = 3600;

    public function __construct(public Lesson $lesson)
    {
    }

    public function handle(): void
    {
        $lowBitrateFormat = (new X264)->setKiloBitrate(500);
        $midBitrateFormat = (new X264)->setKiloBitrate(1500);
        $highBitrateFormat = (new X264)->setKiloBitrate(3000);
        // add
        $p = 'lessons/' . $this->lesson->module->title . '/' . $this->lesson->title . '/' . $this->lesson->id . '.m3u8';
        $vid = FFMpeg::fromDisk($this->lesson->disk)
            ->open($this->lesson->video_source)
            ->exportForHLS()
            ->toDisk($this->lesson->disk)
            ->addFormat($lowBitrateFormat)
            ->addFormat($midBitrateFormat)
            ->addFormat($highBitrateFormat)
            ->save($p);
        // TODO ADD VTT suport
        Storage::delete($this->lesson->video_source);


        try {
            $duration = $vid->getDurationInSeconds();


        } catch (Exception $exception) {
            $duration = $this->lesson->duration ?? 0;
            Log::warning('Could not extract video duration: ' . $exception->getMessage());
        }

        $this->lesson->update([
            'duration' => $duration,
            'video_source' => $p,
            'status' => 'READY',
        ]);

    }
}
