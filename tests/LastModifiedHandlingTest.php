<?php

namespace Abordage\LastModified\Tests;

use Abordage\LastModified\Facades\LastModified;
use Abordage\LastModified\LastModifiedServiceProvider;
use Abordage\LastModified\Middleware\LastModifiedHandling;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;

class LastModifiedHandlingTest extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::any('/dummy-post-without-last-modified', function () {
            LastModified::set(null);

            return 'nice';
        })->middleware(LastModifiedHandling::class);

        Route::any('/dummy-post-yesterday-update', function () {
            LastModified::set(now()->subDay());

            return 'nice';
        })->middleware(LastModifiedHandling::class);

        Route::any('/dummy-post-today-update', function () {
            LastModified::set(now());

            return 'nice';
        })->middleware(LastModifiedHandling::class);
    }

    protected function getPackageProviders($app): array
    {
        return [
            LastModifiedServiceProvider::class,
        ];
    }

    public function testHandle(): void
    {
        $this->get('/dummy-post-without-last-modified')->assertOk();
        $this->get('/dummy-post-yesterday-update')->assertOk();
        $this->get('/dummy-post-today-update')->assertOk();

        $headers = ['If-Modified-Since' => now()->subHour()->toRfc7231String()];

        $this->get('/dummy-post-without-last-modified', $headers)->assertOk();
        $this->get('/dummy-post-today-update', $headers)->assertOk();

        $this->get('/dummy-post-yesterday-update', $headers)->assertStatus(304);
        $this->post('/dummy-post-yesterday-update', [], $headers)->assertOk();

        config(['last-modified.enable' => false]);
        $this->get('/dummy-post-yesterday-update', $headers)->assertOk();
    }
}
