<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    LaravelFrontendPresets\NowUiPreset\NowUiPresetServiceProvider::class,
    Barryvdh\DomPDF\ServiceProvider::class,
    App\Providers\BroadcastServiceProvider::class,
];