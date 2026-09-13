<?php

namespace Innoboxrr\LaravelAudit\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Innoboxrr\LaravelAudit\Support\Traits\Auditable;
use Innoboxrr\LaravelAudit\Support\Traits\LoginAttempts;

/**
 * El usuario del host tal como lo documenta el README: auditable, con
 * registro de intentos de login y con isAdmin().
 */
class User extends Authenticatable
{
    use Auditable, LoginAttempts;

    protected $table = 'users';

    protected $guarded = [];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }
}
