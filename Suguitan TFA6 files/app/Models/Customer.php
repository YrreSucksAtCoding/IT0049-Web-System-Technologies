<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Customer model.
 *
 * This one class replaces every query I would otherwise write by hand.
 * Customer::all() becomes SELECT * FROM customers, and each row comes back
 * as a Customer object instead of an associative array.
 *
 * Conventions Eloquent applies without being told:
 *   - the table is the plural of the class name  -> customers
 *   - the primary key is "id"
 *   - created_at / updated_at are maintained automatically
 */
class Customer extends Model
{
    // Stated explicitly even though "customers" is what Eloquent would
    // have guessed, so the mapping is obvious to anyone reading the file.
    protected $table = 'customers';

    /**
     * Columns that may be filled from an array, as in Customer::create($row).
     * Anything not listed here is ignored, which is what stops a form from
     * quietly writing to a column it was never meant to touch.
     */
    protected $fillable = [
        'full_name',
        'email',
        'phone',
    ];
}
