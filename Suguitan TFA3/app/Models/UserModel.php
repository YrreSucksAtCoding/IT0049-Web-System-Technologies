<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UserModel
 *
 * Represents the `users` table — the staff accounts of the POS.
 * The avatar column holds only a filename, never a full path.
 */
class UserModel extends Model
{
    // Which table this model talks to.
    protected $table = 'users';

    // The primary key column.
    protected $primaryKey = 'id';

    // Records come back as associative arrays, so views use $user['username'].
    protected $returnType = 'array';

    // Only these columns can be written by insert() or update().
    // Anything else in the submitted form data is dropped, which is what
    // stops someone adding a field the form never showed.
    protected $allowedFields = ['username', 'full_name', 'avatar', 'created_at'];

    // Let CodeIgniter fill created_at automatically on insert.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';   // this table has no updated_at column

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
