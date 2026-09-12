<?php

namespace App\Controllers;

/**
 * Pages controller
 *
 * Holds the simple pages that do not show any records:
 * the landing page and the about page.
 */
class Pages extends BaseController
{
    /**
     * Landing page.
     *
     * Route: GET /
     * Shows a welcome message and the app title.
     * Returns the rendered HTML of app/Views/pages/home.php
     */
    public function index()
    {
        // Data we want the view to display.
        $data = [
            'title' => 'Home',
        ];

        // view() builds the HTML string, return sends it to the browser.
        return view('pages/home', $data);
    }

    /**
     * About page.
     *
     * Route: GET /about
     * Shows a short description of the POS system.
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
