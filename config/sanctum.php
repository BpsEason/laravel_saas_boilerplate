<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stateful Domains
    |--------------------------------------------------------------------------
    |
    | Requests from the following domains / hosts will receive an XSRF-TOKEN
    | cookie when Laravel Sanctum's authentication guard is used. This is
    | primarily useful for preventing CSRF attacks when originating from
    | first party SPA's using the same domain or a subdomain.
    |
    | By default, we will allow all HTTP_HOST's to receive an XSRF-TOKEN cookie.
    | However, you may customize this list to be more restrictive.
    |
    */

    'stateful_domains' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000',
        env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
    ))),

    /*
    |--------------------------------------------------------------------------
    | Sanctum Guard
    |--------------------------------------------------------------------------
    |
    | This value configures the authentication guard that will be used while
    | authenticating your users. You may use any Laravel authentication
    | guard here that implements the necessary Authenticate interface.
    |
    */

    'guard' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Expiration Minutes
    |--------------------------------------------------------------------------
    |
    | This value controls the number of minutes until an issued personal access
    | token will expire. This is the default value for token expiration and
    | may be overridden when creating a token.
    |
    */

    'expiration' => null,

    /*
    |--------------------------------------------------------------------------
    | Personal Access Token Middleware
    |--------------------------------------------------------------------------
    |
    | This middleware will be assigned to every route that uses the 'sanctum'
    | authentication guard. You may add your own middleware to this array
    | and it will be run during personal access token authentication.
    |
    */

    'middleware' => [
        'authenticate_session' => Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,
        'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
        'validate_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
    ],

];
