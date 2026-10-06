<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 *
 * Routes are split into two halves:
 *   - public routes anyone can reach, including the login page itself
 *   - a group wrapped in the 'auth' filter, which requires a session
 *
 * Putting the guard on the group rather than inside each controller means
 * a new protected page is protected the moment its route is added here.
 */

// ---- public ----
$routes->get('/',      'Pages::index');
$routes->get('about',  'Pages::about');

$routes->get('login',          'Auth::login');     // the form
$routes->post('login/attempt', 'Auth::attempt');   // handle the form
$routes->post('logout',        'Auth::logout');    // POST, never a plain link

// ---- everything below requires a logged-in session ----
$routes->group('', ['filter' => 'auth'], static function ($routes) {

    // customer accounts
    $routes->get('customers',                'Customers::index');
    $routes->get('customers/new',            'Customers::create');
    $routes->post('customers/store',         'Customers::store');
    $routes->get('customers/edit/(:num)',    'Customers::edit/$1');
    $routes->post('customers/update/(:num)', 'Customers::update/$1');

    // user (staff) accounts
    $routes->get('users',                'Users::index');
    $routes->get('users/new',            'Users::create');
    $routes->post('users/store',         'Users::store');
    $routes->get('users/edit/(:num)',    'Users::edit/$1');
    $routes->post('users/update/(:num)', 'Users::update/$1');
});
