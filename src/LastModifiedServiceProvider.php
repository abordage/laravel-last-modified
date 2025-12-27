<?php

declare(strict_types=1);

namespace Abordage\LastModified;

use Illuminate\Support\ServiceProvider;

class LastModifiedServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/last-modified.php' => config_path('last-modified.php'),
            ], 'last-modified-config');
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/last-modified.php', 'last-modified');
        $this->app->singleton('laravel-last-modified', fn() => new LastModified());
    }
}
