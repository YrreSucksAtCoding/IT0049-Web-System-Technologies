<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * Auth controller
 *
 * Login and logout. These routes stay OUTSIDE the 'auth' filter group —
 * a visitor has to be able to reach the login page while logged out.
 */
class Auth extends BaseController
{
    /**
     * Show the login form.
     *
     * Route: GET /login (public)
     * Someone already logged in is sent to the task list instead of being
     * shown the form again. The loggedOut flag turns the confirmation
     * message on, because flash data cannot survive the session being
     * destroyed.
     * Returns the rendered HTML of app/Views/auth/login.php
     */
    public function login()
    {
        if (session()->get('logged_in') === true) {
            return redirect()->to(site_url('tasks'));
        }

        return view('auth/login', [
            'title'     => 'Login',
            'loggedOut' => $this->request->getGet('logout') === '1',
        ]);
    }

    /**
     * Check the submitted credentials and start a session.
     *
     * Route: POST /login/attempt (public)
     * Verifies the typed password against the stored hash with
     * password_verify(). Every failure returns the SAME message, so the
     * page never reveals which usernames exist.
     */
    public function attempt()
    {
        if (! $this->validate(['username' => 'required', 'password' => 'required'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please enter both a username and a password.');
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = (new UserModel())->getByUsername($username);

        // One branch for every failure. Two different messages would tell
        // an attacker which usernames are real.
        if ($user === null || ! password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid username or password.');
        }

        // New session id BEFORE anything is written into the session.
        // Without this, an id someone already holds becomes a logged-in
        // session the moment this login succeeds.
        session()->regenerate(true);

        session()->set([
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'full_name' => $user['full_name'],
            'logged_in' => true,
        ]);

        // Go where they were originally headed, if the filter recorded it.
        $target = session()->get('redirect_url') ?? site_url('tasks');
        session()->remove('redirect_url');

        return redirect()->to($target)
            ->with('message', 'Welcome back, ' . $user['full_name'] . '.');
    }

    /**
     * Destroy the session and return to the login page.
     *
     * Route: POST /logout
     * POST rather than GET, so it cannot be triggered by anything that
     * merely loads a URL. The ?logout=1 flag carries the confirmation,
     * since flash data cannot survive session destruction.
     */
    public function logout()
    {
        session()->remove(['user_id', 'username', 'full_name', 'logged_in', 'redirect_url']);
        session()->destroy();

        return redirect()->to(site_url('login') . '?logout=1');
    }
}
