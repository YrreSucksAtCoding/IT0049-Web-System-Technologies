<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UserModel
 *
 * Represents the `users` table — the staff accounts of the POS.
 * The `password` column holds a bcrypt HASH produced by password_hash().
 * The typed password is never stored anywhere.
 * The `avatar` column holds only a filename, never a full path.
 */
class UserModel extends Model
{
    // Which table this model talks to.
    protected $table = 'users';

    // The primary key column.
    protected $primaryKey = 'id';

    // Records come back as associative arrays.
    protected $returnType = 'array';

    // Only these columns can be written by insert() or update().
    protected $allowedFields = ['username', 'password', 'full_name', 'avatar', 'created_at'];

    // Let CodeIgniter fill created_at automatically on insert.
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
     * Get every user account, sorted by username A to Z.
     *
     * Uses Query Builder (orderBy + findAll), not raw SQL.
     * Returns an array of user records.
     */
    public function getAllUsers()
    {
        return $this->orderBy('username', 'ASC')->findAll();
    }

    /**
     * Count how many user accounts exist.
     *
     * Returns an integer.
     */
    public function countUsers()
    {
        return $this->countAllResults();
    }
}
