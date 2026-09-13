<?php

namespace Innoboxrr\LaravelAudit\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Un usuario de host sin isAdmin() ni isAllowTo(): las policies tienen que
 * negar limpio, no reventar con un metodo inexistente.
 */
class PlainUser extends Authenticatable
{
    protected $table = 'users';

    protected $guarded = [];
}
