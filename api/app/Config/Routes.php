<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group('api', ['namespace' => 'App\Controllers\Api\V1'], static function ($routes) {
    $routes->options('companies', 'CompaniesController::options');
    $routes->options('companies/(:num)', 'CompaniesController::options/$1');
    $routes->resource('companies', ['controller' => 'CompaniesController'], ['except' => ['new', 'edit']]);

    $routes->get('companies/(:num)/collection-routes', 'CollectionRoutesController::byCompany/$1');
    $routes->options('companies/(:num)/collection-routes', 'CollectionRoutesController::options/$1');

    $routes->options('collection-routes', 'CollectionRoutesController::options');
    $routes->options('collection-routes/(:num)', 'CollectionRoutesController::options/$1');
    $routes->resource('collection-routes', ['controller' => 'CollectionRoutesController'], ['except' => ['new', 'edit']]);
});
