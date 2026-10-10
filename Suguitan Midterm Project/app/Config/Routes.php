<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 *
 * Only the login routes are public. Every management page sits inside the
 * 'auth' filtered group, so the guard is written once rather than being
 * repeated at the top of each controller method.
 *
 * Every action that changes data is a POST. GET routes only ever show a
 * page or a form, which keeps links safe to follow and makes a refresh
 * harmless.
 */

// ---- public ----
$routes->get('login',          'Auth::login');
$routes->post('login/attempt', 'Auth::attempt');
$routes->post('logout',        'Auth::logout');

// ---- everything below requires a logged-in staff member ----
$routes->group('', ['filter' => 'auth'], static function ($routes) {

    $routes->get('/', 'Dashboard::index');

    // -- products --
    $routes->get('products',                 'Products::index');
    $routes->get('products/new',             'Products::create');
    $routes->post('products/store',          'Products::store');
    $routes->get('products/edit/(:num)',     'Products::edit/$1');
    $routes->post('products/update/(:num)',  'Products::update/$1');
    $routes->post('products/delete/(:num)',  'Products::delete/$1');
    $routes->get('products/archived',        'Products::archived');
    $routes->post('products/restore/(:num)', 'Products::restore/$1');

    // -- customers --
    $routes->get('customers',                 'Customers::index');
    $routes->get('customers/new',             'Customers::create');
    $routes->post('customers/store',          'Customers::store');
    $routes->get('customers/edit/(:num)',     'Customers::edit/$1');
    $routes->post('customers/update/(:num)',  'Customers::update/$1');
    $routes->post('customers/delete/(:num)',  'Customers::delete/$1');
    $routes->get('customers/archived',        'Customers::archived');
    $routes->post('customers/restore/(:num)', 'Customers::restore/$1');

    // -- staff (users) --
    $routes->get('staff',                 'Staff::index');
    $routes->get('staff/new',             'Staff::create');
    $routes->post('staff/store',          'Staff::store');
    $routes->get('staff/edit/(:num)',     'Staff::edit/$1');
    $routes->post('staff/update/(:num)',  'Staff::update/$1');
    $routes->post('staff/delete/(:num)',  'Staff::delete/$1');
    $routes->get('staff/archived',        'Staff::archived');
    $routes->post('staff/restore/(:num)', 'Staff::restore/$1');

    // -- sales --
    $routes->get('sales',       'Sales::index');    // history
    $routes->get('sales/new',   'Sales::create');   // record a sale
    $routes->post('sales/store', 'Sales::store');
});
