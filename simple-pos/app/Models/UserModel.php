<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UserModel
 *
 * Represents the `users` table — the staff accounts of the POS.
 * Same idea as CustomerModel: it wraps one table and gives us
 * Query Builder methods instead of raw SQL.
 */
class UserModel extends Model
{
    // Which table this model talks to.
    protected $table = 'users';

    // The primary key column of that table.
    protected $primaryKey = 'id';

    // Records come back as associative arrays, so the view keeps
    // using $user['username'] like it did with the static array.
    protected $returnType = 'array';

    // Columns insert() and update() are allowed to write to.
    protected $allowedFields = ['username', 'full_name', 'created_at'];

    // Let CodeIgniter fill created_at automatically on insert.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';   // our table has no updated_at column

    /**
     * Get every user account, sorted by username A to Z.
     *
     * Uses Query Builder (orderBy + findAll), not raw SQL.
     * Returns an array of user records, one array per row.
     */
    public function getAllUsers()
    {
        return $this->orderBy('username', 'ASC')->findAll();
    }

    /**
     * Count how many user accounts exist.
     *
     * Returns an integer, used for the "Total users" line in the view.
     */
    public function countUsers()
    {
        return $this->countAllResults();
    }
}
