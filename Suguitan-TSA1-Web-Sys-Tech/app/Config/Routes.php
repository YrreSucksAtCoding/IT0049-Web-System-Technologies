<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 *
 * All four URLs of the Tasks for Today Management System.
 * Format: $routes->get('the-url', 'ControllerName::methodName');
 *
 * The default 'Home::index' route from a fresh CodeIgniter install is
 * replaced below — our Home controller shows today's tasks instead.
 */

// Welcome page — today's tasks only  ->  http://localhost:8080/
$routes->get('/', 'Home::index');

// Task List — every task  ->  http://localhost:8080/tasks
$routes->get('tasks', 'Tasks::index');

// Profile — the one demo user  ->  http://localhost:8080/profile
$routes->get('profile', 'Profile::index');

// About — static developer page  ->  http://localhost:8080/about
$routes->get('about', 'Pages::about');
