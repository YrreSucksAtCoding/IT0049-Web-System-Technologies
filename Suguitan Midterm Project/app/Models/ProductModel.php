<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ProductModel
 *
 * Represents the `products` table.
 *
 * Deleting is SOFT. A product referenced by a sale cannot be removed
 * without breaking the foreign key, and removing it would also erase what
 * an old receipt was for. Archiving hides it from the management lists
 * while keeping the row, so every listing query excludes archived rows.
 */
class ProductModel extends Model
{
    protected $table      = 'products';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['name', 'price', 'stock_quantity', 'image', 'is_archived', 'created_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';   // this table has no updated_at column

    /**
     * Every product still on the shelf list, cheapest name order.
     *
     * Archived rows are excluded here rather than in the controller, so
     * there is one place where "archived means hidden" is decided.
     * Returns an array of product records.
     */
    public function getActive()
    {
        return $this->where('is_archived', 0)->orderBy('name', 'ASC')->findAll();
    }

    /**
     * The products that have been archived.
     *
     * Returns an array of product records.
     */
    public function getArchived()
    {
        return $this->where('is_archived', 1)->orderBy('name', 'ASC')->findAll();
    }

    /**
     * Products that can actually be sold right now.
     *
     * Not archived AND with stock left, so the Record Sale dropdown never
     * offers something that must then be rejected.
     * Returns an array of product records.
     */
    public function getSellable()
    {
        return $this->where('is_archived', 0)
                    ->where('stock_quantity >', 0)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Products at or below a stock threshold, for the dashboard warning.
     *
     * Takes the threshold. Returns an array of product records.
     */
    public function getLowStock(int $threshold = 10)
    {
        return $this->where('is_archived', 0)
                    ->where('stock_quantity <=', $threshold)
                    ->orderBy('stock_quantity', 'ASC')
                    ->findAll();
    }

    /**
     * Take sold units off a product's stock.
     *
     * Takes the product id and the quantity sold. The subtraction is done
     * in SQL rather than by reading the value, changing it in PHP and
     * writing it back, so two sales at the same moment cannot both read
     * the same starting number.
     * Returns true on success.
     */
    public function decreaseStock(int $id, int $quantity)
    {
        return $this->db->table($this->table)
            ->set('stock_quantity', 'stock_quantity - ' . (int) $quantity, false)
            ->where('id', $id)
            ->where('stock_quantity >=', $quantity)   // never below zero
            ->update();
    }

    /** Hide a product from the lists without deleting the row. */
    public function archive(int $id)
    {
        return $this->update($id, ['is_archived' => 1]);
    }

    /** Bring an archived product back. */
    public function restore(int $id)
    {
        return $this->update($id, ['is_archived' => 0]);
    }
}
