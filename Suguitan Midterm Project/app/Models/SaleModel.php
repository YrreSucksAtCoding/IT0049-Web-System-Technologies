<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * SaleModel
 *
 * Represents the `sales` table — the transaction record that ties a
 * product, an optional customer and the staff member together.
 *
 * Sales are never edited or deleted. A transaction is a historical fact;
 * changing one would make the stock figures stop matching the history.
 */
class SaleModel extends Model
{
    protected $table      = 'sales';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['product_id', 'customer_id', 'sold_by', 'quantity', 'total_price', 'created_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * Sales history, newest first, with the names joined in.
     *
     * The sales table stores ids. Looking each one up separately in the
     * view would be one query per row, so the three joins happen here in
     * a single query instead.
     *
     * The customers join is a LEFT join because customer_id is nullable —
     * a walk-in sale has no customer, and an inner join would hide it.
     *
     * Takes an optional row limit. Returns an array of sale records with
     * product_name, customer_name and staff_name added.
     */
    public function getHistory(?int $limit = null)
    {
        $builder = $this->select('sales.*,
                                  products.name       AS product_name,
                                  customers.full_name AS customer_name,
                                  users.full_name     AS staff_name')
            ->join('products',  'products.id = sales.product_id')
            ->join('customers', 'customers.id = sales.customer_id', 'left')
            ->join('users',     'users.id = sales.sold_by')
            ->orderBy('sales.created_at', 'DESC')
            ->orderBy('sales.id', 'DESC');

        return $limit === null ? $builder->findAll() : $builder->findAll($limit);
    }

    /**
     * Total money taken across all recorded sales.
     *
     * Returns a float, used on the dashboard.
     */
    public function getTotalRevenue(): float
    {
        $row = $this->selectSum('total_price')->first();

        return (float) ($row['total_price'] ?? 0);
    }

    /**
     * How many units have been sold in total.
     *
     * Returns an integer.
     */
    public function getTotalUnitsSold(): int
    {
        $row = $this->selectSum('quantity')->first();

        return (int) ($row['quantity'] ?? 0);
    }
}
