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
     * About page.
     *
     * Route: GET /about (public)
     * Identifies the developer of the system.
     * Returns the rendered HTML of app/Views/pages/about.php
     */
    public function about()
    {
        return view('pages/about', ['title' => 'About']);
    }
}
