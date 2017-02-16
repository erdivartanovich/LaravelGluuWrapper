# Laravel Gluu Wrapper

This package provides an integration for your laravel application with Gluu server.

## Installation

To install this package you should add these lines into your `composer.json`

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/refactory-id/LaravelGluuWrapper"
    }
  ],
  
  "require": {
    "refactory-id/laravel-gluu-wrapper": "dev-master"
  },
  
  "prefer-stable": true,
  "minimum-stability": "dev"
}
```

> Since this package is in its early state, you have to include `prefer-stable` and set `minimum-stability` to `true`.

After that, run `composer update` from your terminal.

To use this package, add `Refactory\LaravelGluuWrapper\ServiceProvider::class` into your service provider configuration inside `config/app.php`

If you want to use facade, add `'GluuWrapper' => Refactory\LaravelGluuWrapper\Facades\GluuWrapper::class,` into `aliases` inside your `config/app.php` 

## Configuration

1. Run `php artisan vendor:publish`
2. Migrate the `access_tokens` table using `php artisan migrate`
3. Modify `config/gluu-wrapper.php` with your environment configuration

## Using Middleware

The middleware in this library depends on [`tymon/jwt-auth`](https://github.com/tymondesigns/jwt-auth). Make sure your application use this library too.

To use the middleware, add `'gluu' => \Refactory\LaravelGluuWrapper\Middleware\GluuToken::class,` in your `app\Http\Kernel.php` file, and use `gluu` middleware.
