<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\ImageUploader;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Staff controller
 *
 * Staff (user) management: list, add, edit, archive, restore — including
 * avatar upload and hashed passwords.
 *
 * Passwords are hashed with password_hash() before they reach the model.
 * The typed password is never written to the database, a log, or the
 * session, and the form never pre-fills it, because what is stored is a
 * hash rather than a password.
 */
class Staff extends BaseController
{
    /** Folder under public/uploads where avatars are stored. */
    private const FOLDER = 'avatars';

    /**
     * Validation rules for the staff form.
     *
     * Takes the id being edited, or null when adding.
     * A password is required when adding — an account without one could
     * never log in. On edit it is optional: blank means keep the current
     * password.
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

        $avatar = $this->request->getFile('avatar');

        if (ImageUploader::wasSubmitted($avatar)) {
            $rules['avatar'] = ImageUploader::rulesFor('avatar');
        }

        return $rules;
    }

    /**
     * Staff list.
     *
     * Route: GET /staff
     * Returns the rendered HTML of app/Views/staff/index.php
     */
    public function index()
    {
        return view('staff/index', [
            'title' => 'Staff',
            'staff' => (new UserModel())->getActive(),
        ]);
    }

    /**
     * Blank New Staff form.
     *
     * Route: GET /staff/new
     * Returns the rendered HTML of app/Views/staff/form.php
     */
    public function create()
    {
        return view('staff/form', [
            'title'   => 'New Staff Member',
            'heading' => 'Add a Staff Member',
            'member'  => null,
            'action'  => site_url('staff/store'),
        ]);
    }

    /**
     * Save a brand new staff member, with a hashed password.
     *
     * Route: POST /staff/store
     */
    public function store()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username'    => $this->request->getPost('username'),
            'full_name'   => $this->request->getPost('full_name'),
            // bcrypt hash. The typed password itself is never saved.
            'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'is_archived' => 0,
        ];

        $avatar = ImageUploader::store($this->request->getFile('avatar'), self::FOLDER, null, 200, 200);

        if ($avatar !== null) {
            $data['avatar'] = $avatar;
        }

        (new UserModel())->insert($data);

        return redirect()->to(site_url('staff'))->with('message', 'Staff member added.');
    }

    /**
     * Edit form, pre-filled with the existing record.
     *
     * Route: GET /staff/edit/3
     * Returns the rendered HTML of app/Views/staff/form.php
     */
    public function edit(int $id)
    {
        $member = (new UserModel())->find($id);

        if ($member === null) {
            throw PageNotFoundException::forPageNotFound('No staff member with id ' . $id);
        }

        return view('staff/form', [
            'title'   => 'Edit Staff Member',
            'heading' => 'Edit ' . $member['full_name'],
            'member'  => $member,
            'action'  => site_url('staff/update/' . $id),
        ]);
    }

    /**
     * Save changes to an existing staff member.
     *
     * Route: POST /staff/update/3
     * The password is only re-hashed and written when a new one was typed;
     * a blank field leaves the stored hash untouched.
     */
    public function update(int $id)
    {
        $userModel = new UserModel();
        $member    = $userModel->find($id);

        if ($member === null) {
            throw PageNotFoundException::forPageNotFound('No staff member with id ' . $id);
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

        $avatar = ImageUploader::store(
            $this->request->getFile('avatar'),
            self::FOLDER,
            $member['avatar'] ?? null,
            200,
            200
        );

        if ($avatar !== null) {
            $data['avatar'] = $avatar;
        }

        $userModel->update($id, $data);

        return redirect()->to(site_url('staff'))->with('message', 'Staff member updated.');
    }

    /**
     * Archive a staff member — the soft delete.
     *
     * Route: POST /staff/delete/3
     * The row stays so their sales history survives. Archiving the account
     * you are signed in with is refused, because that would lock you out
     * of the system mid-session.
     */
    public function delete(int $id)
    {
        $userModel = new UserModel();

        if ($userModel->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('No staff member with id ' . $id);
        }

        if ($id === (int) session()->get('user_id')) {
            return redirect()->to(site_url('staff'))
                ->with('error', 'You cannot archive the account you are signed in with.');
        }

        $userModel->archive($id);

        return redirect()->to(site_url('staff'))
            ->with('message', 'Staff member archived. Their sales history is kept.');
    }

    /**
     * Archived staff accounts.
     *
     * Route: GET /staff/archived
     * Returns the rendered HTML of app/Views/staff/archived.php
     */
    public function archived()
    {
        return view('staff/archived', [
            'title' => 'Archived Staff',
            'staff' => (new UserModel())->getArchived(),
        ]);
    }

    /**
     * Re-enable an archived staff account.
     *
     * Route: POST /staff/restore/3
     */
    public function restore(int $id)
    {
        (new UserModel())->restore($id);

        return redirect()->to(site_url('staff/archived'))->with('message', 'Staff member restored.');
    }
}
