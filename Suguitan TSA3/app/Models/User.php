<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * User model — the single profile record.
 *
 * This REPLACES the User model Laravel ships with, which expects name,
 * email and password columns.
 *
 * It extends plain Model rather than Authenticatable because nothing in
 * this activity logs in. If authentication is added later, the base class
 * changes to Illuminate\Foundation\Auth\User and a password column goes
 * into a new migration.
 */
class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'username',
        'full_name',
        'email',
    ];
}
