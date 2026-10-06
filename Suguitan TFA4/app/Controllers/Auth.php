<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * Auth controller
 *
 * Login and logout. These routes are deliberately OUTSIDE the 'auth'
 * filter group — a visitor has to be able to reach the login page while
 * logged out, otherwise the filter would redirect them to a page the
 * filter also blocks.
 */
class Auth extends BaseController
{
    /**
     * Show the login form.
     *
     * Route: GET /login
     * Someone already logged in is sent to the dashboard instead of being
     * shown the form again. The loggedOut flag turns the "you have been
     * logged out" confirmation on, since flash data cannot survive the
     * session being destroyed.
     * Returns the rendered HTML of app/Views/auth/login.php
     */
    public function login()
    {
        if (session()->get('logged_in') === true) {
            return redirect()->to(site_url('customers'));
        }

        return view('auth/login', [
            'title'     => 'Login',
            'loggedOut' => $this->request->getGet('logout') === '1',
        ]);
    }

    /**
     * Check the submitted credentials and start a session.
     *
     * Route: POST /login/attempt
     * Verifies the typed password against the stored hash with
     * password_verify(). Every failure returns the SAME message, so the
     * page never reveals which usernames exist.
     */
    public function attempt()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please enter both a username and a password.');
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = (new UserModel())->getByUsername($username);

        // One branch for every failure: unknown username, wrong password.
        // Two different messages would tell an attacker which usernames
        // are real, turning guessing into a much smaller job.
        if ($user === null || ! password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid username or password.');
        }

        // Issue a brand new session id BEFORE storing anything in it.
        // Without this, a session id someone already holds becomes a
        // logged-in session the moment this login succeeds.
        session()->regenerate(true);

        // Store only what the app needs. The password hash never goes in here.
        session()->set([
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'full_name' => $user['full_name'],
            'logged_in' => true,
        ]);

        // Send them where they were originally headed, if the filter
        // recorded it; otherwise to the customer list.
        $target = session()->get('redirect_url') ?? site_url('customers');
        session()->remove('redirect_url');

        return redirect()->to($target)
            ->with('message', 'Welcome back, ' . $user['full_name'] . '.');
    }

    /**
     * Destroy the session and return to the login page.
     *
     * Route: POST /logout
     * POST rather than GET, so the action cannot be triggered by anything
     * that merely loads a URL (an image tag on another site, for example).
     * The ?logout=1 flag carries the confirmation across, because flash
     * data cannot survive the session being destroyed.
     */
    public function logout()
    {
        session()->remove(['user_id', 'username', 'full_name', 'logged_in', 'redirect_url']);
        session()->destroy();

        return redirect()->to(site_url('login') . '?logout=1');
    }
}
