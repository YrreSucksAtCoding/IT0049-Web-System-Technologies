<?php

namespace App\Controllers;

/**
 * Pages controller
 *
 * The static pages that need no database at all.
 */
class Pages extends BaseController
{
    /**
     * Landing page.
     *
     * Route: GET /
     * Returns the rendered HTML of app/Views/pages/home.php
     */
    public function index()
    {
        return view('pages/home', ['title' => 'Home']);
    }

    /**
     * About page.
     *
     * Route: GET /about
     * Returns the rendered HTML of app/Views/pages/about.php
     */
    public function about()
    {
        return view('pages/about', ['title' => 'About']);
    }
}
