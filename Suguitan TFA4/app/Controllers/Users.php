<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Users controller
 *
 * User (staff) Accounts: list, add, edit, avatar upload, and the password
 * each account logs in with.
 *
 * Passwords are hashed with password_hash() before they are saved. The
 * typed password is never written to the database, a log, or the session.
 */
class Users extends BaseController
{
    /** Where uploaded avatars are stored, inside the public folder. */
    private function uploadPath(): string
    {
        return FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'avatars';
    }

    /**
     * Validation rules for the user form.
     *
     * Takes the id being edited, or null when adding.
     * A password is required when adding, because an account with no
     * password can never log in. On edit it is optional — leaving it blank
     * means "keep the current password".
     * Returns the rules array used by $this->validate().
     */
    private function rules(?int $id = null): array
    {
        $uniqueUsername = $id === null
            ? 'is_unique[users.username]'
            : 'is_unique[users.username,id,' . $id . ']';

        $rules = [
            'username'  => 'required|alpha_numeric_punct|min_length[3]|max_length[50]|' . $uniqueUsername,
            'full_name' => 'required|min_length[3]|max_length[100]',
        ];

        if ($id === null) {
            $rules['password']         = 'required|min_length[6]|max_length[72]';
            $rules['password_confirm'] = 'required|matches[password]';
        } else {
            $rules['password']         = 'permit_empty|min_length[6]|max_length[72]';
            $rules['password_confirm'] = 'permit_empty|matches[password]';
        }

        // File rules only apply when a file was actually chosen, so the
        // avatar stays optional without letting a bad upload go unchecked.
        $avatar = $this->request->getFile('avatar');

        if ($avatar !== null && $avatar->getError() !== UPLOAD_ERR_NO_FILE) {
            $rules['avatar'] = 'is_image[avatar]'
                . '|mime_in[avatar,image/jpg,image/jpeg,image/png]'
                . '|ext_in[avatar,jpg,jpeg,png]'
                . '|max_size[avatar,2048]';   // 2048 KB = 2 MB
        }

        return $rules;
    }

    /**
     * Move the uploaded avatar into place and make a display-ready copy.
     *
     * Takes the filename of the previous avatar so it can be deleted.
     * Returns the new filename, or null when no file was submitted.
     */
    private function saveAvatar(?string $oldAvatar = null): ?string
    {
        $file = $this->request->getFile('avatar');

        if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $path = $this->uploadPath();

        if (! is_dir($path)) {
            mkdir($path, 0777, true);
        }

        // getRandomName() ignores whatever the user called the file.
        $newName = $file->getRandomName();
        $file->move($path, $newName);

        $saved = $path . DIRECTORY_SEPARATOR . $newName;

        service('image')
            ->withFile($saved)
            ->fit(200, 200, 'center')
            ->save($saved);

        if ($oldAvatar !== null && $oldAvatar !== '' && is_file($path . DIRECTORY_SEPARATOR . $oldAvatar)) {
            unlink($path . DIRECTORY_SEPARATOR . $oldAvatar);
        }

        return $newName;
    }

    /**
     * User Accounts list, with each user's avatar.
     *
     * Route: GET /users (behind the auth filter)
     * Returns the rendered HTML of app/Views/users/index.php
     */
    public function index()
    {
        return view('users/index', [
            'title' => 'User Accounts',
            'users' => (new UserModel())->getAllUsers(),
        ]);
    }

    /**
     * Blank New User form.
     *
     * Route: GET /users/new
     * Returns the rendered HTML of app/Views/users/form.php
     */
    public function create()
    {
        return view('users/form', [
            'title'   => 'New User',
            'heading' => 'Add a User',
            'user'    => null,
            'action'  => site_url('users/store'),
        ]);
    }

    /**
     * Save a brand new user, with a hashed password and optional avatar.
     *
     * Route: POST /users/store
     */
    public function store()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username'  => $this->request->getPost('username'),
            'full_name' => $this->request->getPost('full_name'),
            // password_hash() produces a bcrypt hash. The typed password
            // itself is never saved.
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ];

        $avatar = $this->saveAvatar();

        if ($avatar !== null) {
            $data['avatar'] = $avatar;   // the filename only
        }

        (new UserModel())->insert($data);

        return redirect()->to(site_url('users'))
            ->with('message', 'User added successfully.');
    }

    /**
     * Edit form, pre-filled with the existing record.
     *
     * Route: GET /users/edit/3
     * Returns the rendered HTML of app/Views/users/form.php
     */
    public function edit(int $id)
    {
        $user = (new UserModel())->find($id);

        if ($user === null) {
            throw PageNotFoundException::forPageNotFound('No user with id ' . $id);
        }

        return view('users/form', [
            'title'   => 'Edit User',
            'heading' => 'Edit User #' . $id,
            'user'    => $user,
            'action'  => site_url('users/update/' . $id),
        ]);
    }

    /**
     * Save changes to an existing user.
     *
     * Route: POST /users/update/3
     * The password is only re-hashed and written when a new one was typed.
     * A blank password field leaves the stored hash untouched.
     */
    public function update(int $id)
    {
        $userModel = new UserModel();
        $user      = $userModel->find($id);

        if ($user === null) {
            throw PageNotFoundException::forPageNotFound('No user with id ' . $id);
        }

        if (! $this->validate($this->rules($id))) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username'  => $this->request->getPost('username'),
            'full_name' => $this->request->getPost('full_name'),
        ];

        $newPassword = $this->request->getPost('password');

        if ($newPassword !== null && $newPassword !== '') {
            $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $avatar = $this->saveAvatar($user['avatar'] ?? null);

        if ($avatar !== null) {
            $data['avatar'] = $avatar;
        }

        $userModel->update($id, $data);

        return redirect()->to(site_url('users'))
            ->with('message', 'User updated successfully.');
    }
}
