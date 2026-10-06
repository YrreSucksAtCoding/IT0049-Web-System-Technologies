<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * CustomerModel
 *
 * Represents the `customers` table.
 */
class CustomerModel extends Model
{
    // Which table this model talks to.
    protected $table = 'customers';

    // The primary key column.
    protected $primaryKey = 'id';

    // Records come back as associative arrays.
    protected $returnType = 'array';

    // Only these columns can be written by insert() or update().
    protected $allowedFields = ['full_name', 'email', 'phone', 'created_at'];

    // Let CodeIgniter fill created_at automatically on insert.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';   // this table has no updated_at column

    /**
     * Get every customer, newest sign-ups first.
     *
     * Uses Query Builder (orderBy + findAll), not raw SQL.
     * Returns an array of customer records.
     */
    public function getAllCustomers()
    {
        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    /**
     * Count how many customers are in the table.
     *
     * Returns an integer.
     */
    public function countCustomers()
    {
        return $this->countAllResults();
    }
}
