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

    $routes->get('collection-routes/(:num)/points', 'CollectionPointsController::byRoute/$1');
    $routes->options('collection-routes/(:num)/points', 'CollectionPointsController::options/$1');
    $routes->options('collection-points', 'CollectionPointsController::options');
    $routes->options('collection-points/(:num)', 'CollectionPointsController::options/$1');
    $routes->resource('collection-points', ['controller' => 'CollectionPointsController'], ['except' => ['new', 'edit']]);

    $routes->options('waste-types', 'WasteTypesController::options');
    $routes->options('waste-types/(:num)', 'WasteTypesController::options/$1');
    $routes->resource('waste-types', ['controller' => 'WasteTypesController'], ['except' => ['new', 'edit']]);

    $routes->get('collection-points/(:num)/records', 'CollectionRecordsController::byPoint/$1');
    $routes->options('collection-points/(:num)/records', 'CollectionRecordsController::options/$1');
    $routes->options('collection-records', 'CollectionRecordsController::options');
    $routes->options('collection-records/(:num)', 'CollectionRecordsController::options/$1');
    $routes->resource('collection-records', ['controller' => 'CollectionRecordsController'], ['except' => ['new', 'edit']]);

    $routes->get('collection-records/(:num)/events', 'CollectionEventsController::byRecord/$1');
    $routes->options('collection-records/(:num)/events', 'CollectionEventsController::options/$1');
    $routes->options('collection-events', 'CollectionEventsController::options');
    $routes->options('collection-events/(:num)', 'CollectionEventsController::options/$1');
    $routes->resource('collection-events', ['controller' => 'CollectionEventsController'], ['except' => ['new', 'edit']]);
});
