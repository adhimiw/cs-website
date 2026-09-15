<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    |
    | This option controls the default AI provider that will be used by the
    | SDK. You may change this default as needed to switch between services.
    |
    */

    'default' => env('AI_PROVIDER', 'openai'),

    /*
    |--------------------------------------------------------------------------
    | AI Providers
    |--------------------------------------------------------------------------
    |
    | Here you may configure the settings for each AI provider used by your
    | application. You can add as many providers as you need.
    |
    */

    'providers' => [

        'openai' => [
            'driver' => 'openai',
            'key' => env('GROQ_API_KEY'),
            'url' => env('GROQ_BASE_URL', 'https://api.groq.com/openai/v1'),
            'models' => [
                'text' => [
                    'default' => 'openai/gpt-oss-120b',
                    'cheapest' => 'openai/gpt-oss-20b',
                    'smartest' => 'openai/gpt-oss-120b',
                ],
            ],
        ],

        'groq' => [
            'driver' => 'groq',
            'key' => env('GROQ_API_KEY'),
            'url' => env('GROQ_BASE_URL', 'https://api.groq.com/openai/v1'),
            'models' => [
                'text' => [
                    'default' => 'openai/gpt-oss-120b',
                    'cheapest' => 'openai/gpt-oss-20b',
                    'smartest' => 'openai/gpt-oss-120b',
                ],
            ],
        ],

        'gemini' => [
            'driver' => 'gemini',
            'key' => env('GEMINI_API_KEY'),
        ],

    ],

];
