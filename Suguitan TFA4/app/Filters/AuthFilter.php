<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AuthFilter
 *
 * A filter runs BEFORE the request reaches the controller, so a visitor
 * who is not logged in never gets as far as the page they asked for.
 *
 * Writing the check here instead of at the top of every controller method
 * means protection is applied in one place. Adding a new protected page is
 * then a matter of putting its route inside the filtered group — there is
 * no per-page check left to forget.
 *
 * Registered as the alias 'auth' in app/Config/Filters.php.
 */
class AuthFilter implements FilterInterface
{
    /**
     * Runs before the controller.
     *
     * Returns a redirect to the login page when nobody is logged in, which
     * stops the request there. Returning nothing lets the request continue
     * to the controller as normal.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('logged_in') !== true) {

            // Remember where they were trying to go, so after a successful
            // login they land there instead of on a generic page.
            session()->set('redirect_url', current_url());

            return redirect()->to(site_url('login'))
                ->with('error', 'Please log in to continue.');
        }
    }

    /**
     * Runs after the controller.
     *
     * Nothing to do here — this filter only guards the way in.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // intentionally empty
    }
}
