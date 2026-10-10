<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UserModel
 *
 * Represents the `users` table — the staff who operate the POS.
 *
 * `password` holds a bcrypt HASH from password_hash(). The typed password
 * is never stored. `avatar` holds a filename only, never a path.
 */
class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['username', 'full_name', 'password', 'avatar', 'is_archived', 'created_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * Find one staff member by username, for the login check.
     *
     * Takes the username exactly as typed. Returns the record, or null.
     * The caller must treat "no user" and "wrong password" as one failure.
     */
    public function getByUsername(string $username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Every staff account that has not been archived.
     *
     * Returns an array of user records.
     */
    public function getActive()
    {
        return $this->where('is_archived', 0)->orderBy('username', 'ASC')->findAll();
    }

    /**
     * The staff accounts that have been archived.
     *
     * An archived account keeps its sales history but can no longer log in.
     * Returns an array of user records.
     */
    public function getArchived()
    {
        return $this->where('is_archived', 1)->orderBy('username', 'ASC')->findAll();
    }

    /** Disable a staff account without deleting the row. */
    public function archive(int $id)
    {
        return $this->update($id, ['is_archived' => 1]);
    }

    /** Re-enable an archived staff account. */
    public function restore(int $id)
    {
        return $this->update($id, ['is_archived' => 0]);
    }
}
