<?php

namespace App\Services;

use InvalidArgumentException;

class VideoStream
{
    private readonly string $path;

    private ?string $stream = null;

    private int $buffer = 102400;

    private int $start = -1;

    private int $end = -1;

    private int $size = 0;

    public function __construct(string $filePath)
    {
        throw_unless(file_exists($filePath), new InvalidArgumentException("File does not exist: {$filePath}"));

        $this->path = $filePath;
    }

    /**
     *      * Open stream
     *           */
    private function open(): void
    {
        $this->stream = fopen($this->path, 'rb');
        if ($this->stream === '') {
            http_response_code(500);
            exit('Could not open stream for reading.');
        }

    }

    /**
     *      * Set proper header to serve the video content
     *           */
    private function setHeader(): void
    {
        ob_get_clean();

        $this->size = filesize($this->path);
        $this->start = 0;
        $this->end = $this->size - 1;

        header('Content-Type: video/mp4');
        header('Cache-Control: max-age=2592000, public');
        header('Expires: '.gmdate('D, d M Y H:i:s', time() + 2592000).' GMT');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($this->path)).' GMT');
        header('Accept-Ranges: bytes');

        if (isset($_SERVER['HTTP_RANGE'])) {
            [, $range] = explode('=', (string) $_SERVER['HTTP_RANGE'], 2);

            if (str_contains($range, ',')) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes 0-{$this->end}/{$this->size}");
                exit;
            }

            if ($range === '-') {
                $this->start = $this->size - (int) substr($range, 1);
            } else {
                [$start, $end] = explode('-', $range);
                $this->start = (int) $start;
                $this->end = ($end !== '' && is_numeric($end)) ? (int) $end : $this->end;
            }

            // Validate range
            if (
                $this->start > $this->end ||
                $this->start > $this->size - 1 ||
                $this->end >= $this->size
            ) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes 0-{$this->end}/{$this->size}");
                exit;
            }

            $length = $this->end - $this->start + 1;

            fseek($this->stream, $this->start);
            header('HTTP/1.1 206 Partial Content');
            header("Content-Length: {$length}");
            header("Content-Range: bytes {$this->start}-{$this->end}/{$this->size}");
        } else {
            header("Content-Length: {$this->size}");
        }

    }

    /**
     *      * close currently opened stream
     *           */
    private function end(): void
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }
    }

    /**
     *      * perform the streaming of calculated range
     *           */
    private function stream(): void
    {
        set_time_limit(0);
        $position = $this->start;

        while (! feof($this->stream) && $position <= $this->end) {
            $bytesToRead = min($this->buffer, $this->end - $position + 1);
            echo fread($this->stream, $bytesToRead);
            flush();
            $position += $bytesToRead;
        }
    }

    /**
     *      * Start streaming video content
     *           */
    public function start(): void
    {
        $this->open();
        $this->setHeader();
        $this->stream();
        $this->end();
    }
}
