<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes('api', ['namespace' => 'App\Controllers\Api\V1'], static function ($routes) {
    $routes->resource('companies');
});
