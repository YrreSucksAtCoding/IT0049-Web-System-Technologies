<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * User model — the staff accounts.
 *
 * This REPLACES the User model Laravel ships with (which expects name,
 * email and password columns). It still extends Authenticatable rather
 * than plain Model, so the same class can be used for logging in later
 * without rewriting it.
 */
class User extends Authenticatable
{
    protected $table = 'users';

    protected $fillable = [
        'username',
        'password',
        'full_name',
        'role',
    ];

    /**
     * Never included when the model is turned into an array or JSON.
     * The Blade view does not print the hash, but without this line the
     * hash still travels into the view with the rest of the record.
     */
    protected $hidden = [
        'password',
    ];
}
