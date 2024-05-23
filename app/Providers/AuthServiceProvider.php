<?php

namespace App\Providers;

use App\Models\Letter;
use App\Policies\LetterPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Letter::class => LetterPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}