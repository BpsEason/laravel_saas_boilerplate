<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    require __DIR__.'/../vendor/autoload.php';
} else {
    # Fallback if composer dependencies aren't installed (e.g., during initial setup)
    echo "<h1>Laravel application not fully set up.</h1>";
    echo "<p>Please run 'composer install' and 'php artisan migrate' (in your Docker container, if applicable).</p>";
    exit(1);
}

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
