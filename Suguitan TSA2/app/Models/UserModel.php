<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UserModel
 *
 * Represents the `users` table.
 * The `password` column holds a bcrypt HASH from password_hash().
 * The typed password itself is never stored.
 */
class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['username', 'password', 'full_name', 'email', 'created_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';   // this table has no updated_at column

    /**
     * Find one user by username, for the login check.
     *
     * Takes the username exactly as typed. Returns the record as an array,
     * or null when no such user exists. The caller must treat "no user" and
     * "wrong password" as the same failure.
     */
    public function getByUsername(string $username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Get the demo user record for the Profile page.
     *
     * first() returns one record instead of a list.
     * Returns one user record as an array, or null if the table is empty.
     */
    public function getDemoUser()
    {
        return $this->orderBy('id', 'ASC')->first();
    }
}
