<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Users controller
 *
 * User (staff) Accounts: list, add, edit, and avatar upload.
 * Only the uploaded file's NAME is stored in the database — the file
 * itself lives in public/uploads/avatars.
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
     * Takes the id being edited, or null when adding. The file rules are
     * only added when a file was actually chosen, so the avatar stays
     * optional without letting a bad upload slip through unchecked.
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
     * Returns the new filename, or null when no file was submitted
     * (which the caller reads as "keep whatever is already there").
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
        // Trusting the original name is how a disguised script gets in.
        $newName = $file->getRandomName();
        $file->move($path, $newName);

        $saved = $path . DIRECTORY_SEPARATOR . $newName;

        // Prepare a display-ready 200x200 square so the listing page is not
        // loading full-size camera photos. This overwrites the moved file.
        service('image')
            ->withFile($saved)
            ->fit(200, 200, 'center')
            ->save($saved);

        // Clear out the file this one replaces, so old avatars do not pile up.
        if ($oldAvatar !== null && $oldAvatar !== '' && is_file($path . DIRECTORY_SEPARATOR . $oldAvatar)) {
            unlink($path . DIRECTORY_SEPARATOR . $oldAvatar);
        }

        return $newName;
    }

    /**
     * User Accounts list, with each user's avatar.
     *
     * Route: GET /users
     * Returns the rendered HTML of app/Views/users/index.php
     */
    public function index()
    {
        $userModel = new UserModel();

        return view('users/index', [
            'title' => 'User Accounts',
            'users' => $userModel->getAllUsers(),
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
     * Save a brand new user, with an optional avatar.
     *
     * Route: POST /users/store
     * Validates, stores the file, then saves only the filename.
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
        ];

        $avatar = $this->saveAvatar();

        if ($avatar !== null) {
            $data['avatar'] = $avatar;   // the filename only, never the path
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
        $userModel = new UserModel();
        $user      = $userModel->find($id);

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
     * Save changes to an existing user, replacing the avatar if a new
     * file was chosen.
     *
     * Route: POST /users/update/3
     * Leaving the file input empty keeps the current avatar.
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

        $avatar = $this->saveAvatar($user['avatar'] ?? null);

        if ($avatar !== null) {
            $data['avatar'] = $avatar;
        }

        $userModel->update($id, $data);

        return redirect()->to(site_url('users'))
            ->with('message', 'User updated successfully.');
    }
}
