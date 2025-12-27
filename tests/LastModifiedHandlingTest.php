<?php

declare(strict_types=1);

namespace Abordage\LastModified\Tests;

use Abordage\LastModified\Facades\LastModified;
use Abordage\LastModified\LastModifiedServiceProvider;
use Abordage\LastModified\Middleware\LastModifiedHandling;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;

class LastModifiedHandlingTest extends Orchestra
{
    private array $headers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->headers = [
            'If-Modified-Since' => now()->subHour()->toRfc7231String(),
        ];

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

    public function testWithoutIfModifiedSinceHeader(): void
    {
        $this->get('/dummy-post-without-last-modified')->assertOk();
        $this->get('/dummy-post-yesterday-update')->assertOk();
        $this->get('/dummy-post-today-update')->assertOk();
    }

    public function testWithIfModifiedSinceHeader(): void
    {
        $this->get('/dummy-post-without-last-modified', $this->headers)->assertOk();
        $this->get('/dummy-post-today-update', $this->headers)->assertOk();
    }

    public function testPostMethod(): void
    {
        $this->post('/dummy-post-yesterday-update', [], $this->headers)->assertOk();
    }

    public function testDisableLastModified(): void
    {
        config(['last-modified.enable' => false]);
        $this->get('/dummy-post-yesterday-update', $this->headers)->assertOk();
        config(['last-modified.enable' => true]);
    }

    public function test304(): void
    {
        $this->get('/dummy-post-yesterday-update', $this->headers)->assertStatus(304);
    }
}
