<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 *
 * Reading the tasks is public. Managing them is not.
 *
 * The Welcome, Task List, Profile and About pages stay open to anyone,
 * exactly as the activity requires. Everything that creates, changes or
 * archives a task sits inside the 'auth' filtered group, so the guard is
 * written once instead of being repeated in each controller method.
 */

// ---- public: anyone can read ----
$routes->get('/',        'Home::index');      // today's tasks
$routes->get('tasks',    'Tasks::index');     // full task list
$routes->get('profile',  'Profile::index');
$routes->get('about',    'Pages::about');

// ---- public: login and logout ----
$routes->get('login',          'Auth::login');
$routes->post('login/attempt', 'Auth::attempt');
$routes->post('logout',        'Auth::logout');

// ---- protected: managing tasks requires a session ----
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('tasks/new',             'Tasks::create');
    $routes->post('tasks/store',          'Tasks::store');
    $routes->get('tasks/edit/(:num)',     'Tasks::edit/$1');
    $routes->post('tasks/update/(:num)',  'Tasks::update/$1');

    // "Delete" archives the row rather than removing it — POST only, so it
    // cannot be triggered by anything that merely loads a URL.
    $routes->post('tasks/delete/(:num)',  'Tasks::delete/$1');

    $routes->get('tasks/archived',        'Tasks::archived');
    $routes->post('tasks/restore/(:num)', 'Tasks::restore/$1');
});
