<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\TravelExperience;
use App\Policies\TravelExperiencePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        TravelExperience::class => TravelExperiencePolicy::class,
    ];
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('create-country', function ($user) {
            return $user->is_admin;
        });
    }
}
