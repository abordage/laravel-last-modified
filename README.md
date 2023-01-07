# Last-Modified / 304 Not Modified Handler for Laravel

Easily setting the `Last-Modified` header and `304 Not Modified` response code.

<p style="text-align: center;" align="center">

<a href="https://packagist.org/packages/abordage/laravel-last-modified" title="Packagist version">
    <img alt="Packagist Version" src="https://img.shields.io/packagist/v/abordage/laravel-last-modified">
</a>

<a href="https://github.com/abordage/laravel-last-modified/actions/workflows/tests.yml" title="GitHub Tests Status">
    <img alt="GitHub Tests Status" src="https://img.shields.io/github/actions/workflow/status/abordage/laravel-last-modified/tests.yml?label=tests">
</a>

<a href="https://github.com/abordage/laravel-last-modified/actions/workflows/php-cs-fixer.yml" title="GitHub Code Style Status">
    <img alt="GitHub Code Style Status" src="https://img.shields.io/github/actions/workflow/status/abordage/laravel-last-modified/php-cs-fixer.yml?label=code%20style">
</a>

<a href="https://www.php.net/" title="PHP version">
    <img alt="PHP Version Support" src="https://img.shields.io/packagist/php-v/abordage/laravel-last-modified">
</a>

<a href="https://github.com/abordage/laravel-last-modified/blob/master/LICENSE.md" title="License">
    <img alt="License" src="https://img.shields.io/github/license/abordage/laravel-last-modified">
</a>

</p>


## Requirements
- PHP 7.4 or higher
- Laravel 8+

## Installation

You can install the package via composer:

```bash
composer require abordage/laravel-last-modified
```

Optionally, you can publish the config file with:

```bash
php artisan vendor:publish --tag="last-modified-config"
```
## Usage
The setup is very simple and consists of two steps:

### Registering middleware

```php
// in app/Http/Kernel.php

protected $middleware = [
    'web' => [
        // other middleware
        
        \Abordage\LastModified\Middleware\LastModifiedHandling::class,
    ],
];
```

### Set Last Update Date in your Controller

```php
<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use LastModified;
 
class PostController extends Controller
{
    public function show($id)
    {
        $post = \App\Models\Post::findOrFail($id);

        LastModified::set($post->updated_at);
        
        return view('posts.show', ['post' => $post]);
    }
}
```
It's all. Now you can check the headers.

## How to check headers

You can check headers in the browser console under the `Network` tab (make sure `Disable Cache` is off) 

**or** 

using https://last-modified.com/en

<p style="text-align: center;" align="center">
    <img alt="Check Last-Modified" src="https://github.com/abordage/laravel-last-modified/blob/master/docs/images/check-last-modified-rounded.png?raw=true">
</p>

## Testing

```bash
composer test:all
```

or

```bash
composer test:phpunit
composer test:phpstan
composer test:phpcsf
```

## Feedback

If you have any feedback, comments or suggestions, please feel free to open an issue within this repository.

## Contributing

Please see [CONTRIBUTING](https://github.com/abordage/.github/blob/master/CONTRIBUTING.md) for details.

## Credits

- [Pavel Bychko](https://github.com/abordage)
- [All Contributors](https://github.com/abordage/laravel-last-modified/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
