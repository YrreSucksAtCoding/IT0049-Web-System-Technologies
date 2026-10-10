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
     * Route: GET /login
     * Someone already signed in goes to the dashboard instead of seeing
     * the form again. The loggedOut flag turns the confirmation on,
     * because flash data cannot survive the session being destroyed.
     * Returns the rendered HTML of app/Views/auth/login.php
     */
    public function login()
    {
        if (session()->get('logged_in') === true) {
            return redirect()->to(site_url('/'));
        }

        return view('auth/login', [
            'title'     => 'Sign in',
            'loggedOut' => $this->request->getGet('logout') === '1',
        ]);
    }

    /**
     * Check the submitted credentials and start a session.
     *
     * Route: POST /login/attempt
     * Verifies the typed password against the stored hash with
     * password_verify(). An archived account is refused even with the
     * right password, and every failure returns the SAME message so the
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

        // One branch for every failure: unknown username, archived
        // account, wrong password. Separate messages would tell an
        // attacker which usernames are real.
        if ($user === null
            || (int) $user['is_archived'] === 1
            || ! password_verify($password, $user['password'])) {

            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid username or password.');
        }

        // A new session id BEFORE anything is written into the session.
        // Without this, an id someone already holds becomes a signed-in
        // session the moment this login succeeds.
        session()->regenerate(true);

        session()->set([
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'full_name' => $user['full_name'],
            'avatar'    => $user['avatar'],
            'logged_in' => true,
        ]);

        // Go where they were originally headed, if the filter recorded it.
        $target = session()->get('redirect_url') ?? site_url('/');
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
        session()->remove(['user_id', 'username', 'full_name', 'avatar', 'logged_in', 'redirect_url']);
        session()->destroy();

        return redirect()->to(site_url('login') . '?logout=1');
    }
}
