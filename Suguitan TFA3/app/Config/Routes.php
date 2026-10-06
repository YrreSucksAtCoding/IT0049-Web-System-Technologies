<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 *
 * All URLs of the POS app.
 * GET routes show pages and forms. POST routes receive submitted forms.
 *
 * Order matters: 'customers/new' is listed before any route with a
 * placeholder so it can never be mistaken for an id.
 */

// ---- static pages ----
$routes->get('/',      'Pages::index');
$routes->get('about',  'Pages::about');

// ---- customer accounts ----
$routes->get('customers',                'Customers::index');   // list
$routes->get('customers/new',            'Customers::create');  // blank form
$routes->post('customers/store',         'Customers::store');   // handle new
$routes->get('customers/edit/(:num)',    'Customers::edit/$1'); // pre-filled form
$routes->post('customers/update/(:num)', 'Customers::update/$1'); // handle edit

// ---- user (staff) accounts ----
$routes->get('users',                'Users::index');
$routes->get('users/new',            'Users::create');
$routes->post('users/store',         'Users::store');
$routes->get('users/edit/(:num)',    'Users::edit/$1');
$routes->post('users/update/(:num)', 'Users::update/$1');
