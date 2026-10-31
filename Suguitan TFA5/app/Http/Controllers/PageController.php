<?php

namespace App\Http\Controllers;

/**
 * PageController
 *
 * Holds the simple pages that show no records: the landing page and the
 * about page. The CodeIgniter equivalent was app/Controllers/Pages.php.
 */
class PageController extends Controller
{
    /**
     * Landing page.
     *
     * Route: GET /
     * Shows a welcome message. The second argument of view() is the data
     * the template can use, exactly like CodeIgniter's view() helper.
     * Returns the rendered resources/views/pages/home.blade.php
     */
    public function index()
    {
        return view('pages.home', [
            'title' => 'Home',
        ]);
    }

    /**
     * About page.
     *
     * Route: GET /about
     * Shows a short description of the POS system.
     * Returns the rendered resources/views/pages/about.blade.php
     */
    public function about()
    {
        return view('pages.about', [
            'title' => 'About',
        ]);
    }
}
