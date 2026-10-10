<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * CustomerModel
 *
 * Represents the `customers` table.
 * Archiving rather than deleting, for the same reason as products: a
 * customer named on a past sale must stay in the table.
 */
class CustomerModel extends Model
{
    protected $table      = 'customers';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['full_name', 'email', 'phone', 'is_archived', 'created_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * Every customer that has not been archived, by name.
     *
     * Returns an array of customer records.
     */
    public function getActive()
    {
        return $this->where('is_archived', 0)->orderBy('full_name', 'ASC')->findAll();
    }

    /**
     * The customers that have been archived.
     *
     * Returns an array of customer records.
     */
    public function getArchived()
    {
        return $this->where('is_archived', 1)->orderBy('full_name', 'ASC')->findAll();
    }

    /** Hide a customer from the lists without deleting the row. */
    public function archive(int $id)
    {
        return $this->update($id, ['is_archived' => 1]);
    }

    /** Bring an archived customer back. */
    public function restore(int $id)
    {
        return $this->update($id, ['is_archived' => 0]);
    }
}
