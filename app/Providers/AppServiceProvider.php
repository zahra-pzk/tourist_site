<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {

    }
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();
        View::share('appName', config('app.name'));
        $this->registerCustomMacros();
    }

    protected function registerCustomMacros(): void
    {
        \Illuminate\Support\Collection::macro('toLimitedText', function (int $limit = 100) {
            return $this->map(function ($item) use ($limit) {
                if (is_string($item) && strlen($item) > $limit) {
                    return substr($item, 0, $limit) . '...';
                }
                return $item;
            });
        });
    }
}
