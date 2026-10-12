<?php

$defaultUrl = env('AI_BASE_URL', env('OPENAI_BASE_URL', env('GROQ_BASE_URL', 'http://host.containers.internal:1234/v1')));
$defaultKey = env('AI_API_KEY', env('OPENAI_API_KEY', env('GROQ_API_KEY', 'local-gemma')));
$defaultModel = env('AI_MODEL', 'google/gemma-4-e4b');

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
            'key' => $defaultKey,
            'url' => $defaultUrl,
            'models' => [
                'text' => [
                    'default' => $defaultModel,
                    'cheapest' => $defaultModel,
                    'smartest' => $defaultModel,
                ],
            ],
        ],

        'groq' => [
            'driver' => 'openai',
            'key' => $defaultKey,
            'url' => $defaultUrl,
            'models' => [
                'text' => [
                    'default' => $defaultModel,
                    'cheapest' => $defaultModel,
                    'smartest' => $defaultModel,
                ],
            ],
        ],

        'gemini' => [
            'driver' => 'gemini',
            'key' => env('GEMINI_API_KEY'),
        ],

    ],

];

