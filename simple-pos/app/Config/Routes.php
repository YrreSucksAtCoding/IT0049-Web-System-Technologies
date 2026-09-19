<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 *
 * Every URL of this POS app is listed here.
 * Format: $routes->get('the-url', 'ControllerName::methodName');
 *
 * The default 'Home::index' route that comes with a fresh CodeIgniter
 * install was removed, because our landing page uses Pages::index instead.
 */

// Landing page  ->  http://localhost:8080/
$routes->get('/', 'Pages::index');

// About page  ->  http://localhost:8080/about
$routes->get('about', 'Pages::about');

// Customer Accounts page  ->  http://localhost:8080/customers
$routes->get('customers', 'Customers::index');

// User Accounts page  ->  http://localhost:8080/users
$routes->get('users', 'Users::index');
