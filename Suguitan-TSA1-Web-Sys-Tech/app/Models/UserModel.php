<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UserModel
 *
 * Represents the `users` table.
 * This system has exactly one demo user, shown on the Profile page.
 */
class UserModel extends Model
{
    // Which table this model talks to.
    protected $table = 'users';

    // The primary key column.
    protected $primaryKey = 'id';

    // Records come back as associative arrays.
    protected $returnType = 'array';

    // Columns insert() and update() are allowed to write to.
    protected $allowedFields = ['username', 'full_name', 'email', 'created_at'];

    // Let CodeIgniter fill created_at automatically on insert.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';   // this table has no updated_at column

    /**
     * Get the demo user record.
     *
     * first() returns a single record instead of a list, which is what
     * the Profile page needs. Returns one user record as an array, or
     * null if the table is empty.
     */
    public function getDemoUser()
    {
        return $this->orderBy('id', 'ASC')->first();
    }
}
