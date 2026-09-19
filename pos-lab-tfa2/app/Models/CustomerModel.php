<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * CustomerModel
 *
 * Represents the `customers` table.
 * Extending CodeIgniter's Model gives us Query Builder methods
 * like findAll(), find() and where() without writing raw SQL.
 */
class CustomerModel extends Model
{
    // Which table this model talks to.
    protected $table = 'customers';

    // The primary key column of that table.
    protected $primaryKey = 'id';

    // Return each record as an associative array, so the view
    // can use $customer['full_name'] exactly like the old static array.
    protected $returnType = 'array';

    // Columns that are allowed to be filled by insert() or update().
    protected $allowedFields = ['full_name', 'email', 'phone', 'created_at'];

    // Let CodeIgniter fill created_at automatically on insert.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';   // our table has no updated_at column

    /**
     * Get every customer, newest sign-ups first.
     *
     * Uses Query Builder (orderBy + findAll), not raw SQL.
     * Returns an array of customer records, one array per row.
     */
    public function getAllCustomers()
    {
        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    /**
     * Count how many customers are in the table.
     *
     * Returns an integer. Used for the "Total customers" line in the view.
     */
    public function countCustomers()
    {
        return $this->countAllResults();
    }
}
