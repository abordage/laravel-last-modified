<?php

namespace Abordage\LastModified\Facades;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void set(Carbon $updated_at)
 * @method static Carbon|null get()
 *
 * @see \Abordage\LastModified\LastModified
 */
class LastModified extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-last-modified';
    }
}
