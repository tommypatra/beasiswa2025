<?php

return [
    'base_url' => env(
        'SEVIMA_BASE_URL',
        'https://api.sevimaplatform.com/'
    ),

    'timeout' => env('SEVIMA_TIMEOUT', 30),

    'max_request' => env(
        'SEVIMA_MAX_REQUEST',
        30
    ),

    'window_seconds' => env(
        'SEVIMA_WINDOW_SECONDS',
        60
    ),
];
