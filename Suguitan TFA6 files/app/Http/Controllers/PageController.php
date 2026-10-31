<?php

namespace App\Http\Controllers;

/**
 * PageController
 *
 * Unchanged from TFA5. The home and about pages show no records, so there
 * was nothing here to move into the database.
 */
class PageController extends Controller
{
    /**
     * Landing page.  Route: GET /
     */
    public function index()
    {
        return view('pages.home', [
            'title' => 'Home',
        ]);
    }

    /**
     * About page.  Route: GET /about
     */
    public function about()
    {
        return view('pages.about', [
            'title' => 'About',
        ]);
    }
}
