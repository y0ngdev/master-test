<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Video Provider
    |--------------------------------------------------------------------------
    |
    | This option controls the default video provider that will be used by the
    | application. By default, this is set to 'file' to use your configured default filesystem for HLS streaming, but you can change
    | it to 'vimeo' in incoming updates .
    |
    | Supported: "file", "vimeo"(next update)
    |
    */
    'driver' => env('VIDEO_DRIVER', 'file_system'),
];
