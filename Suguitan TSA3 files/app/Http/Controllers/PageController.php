<?php

namespace App\Http\Controllers;

/**
 * PageController
 *
 * The static page. It touches no model because it displays no records.
 */
class PageController extends Controller
{
    /**
     * About page.
     *
     * Route: GET /about
     */
    public function about()
    {
        return view('pages.about', [
            'title' => 'About',
        ]);
    }
}
