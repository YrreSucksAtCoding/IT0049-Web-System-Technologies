<?php

namespace App\Controllers;

/**
 * Pages controller
 *
 * Holds the static pages that need no database at all.
 */
class Pages extends BaseController
{
    /**
     * About page.
     *
     * Route: GET /about
     * A static page identifying the developer of the system.
     * No model is used here because there is no data to fetch.
     * Returns the rendered HTML of app/Views/pages/about.php
     */
    public function about()
    {
        $data = [
            'title' => 'About',
        ];

        return view('pages/about', $data);
    }
}
