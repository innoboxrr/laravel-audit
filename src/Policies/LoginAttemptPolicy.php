<?php

namespace Innoboxrr\LaravelAudit\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Innoboxrr\LaravelAudit\Models\LoginAttempt;

/*
 * isAdmin() e isAllowTo() los pone el host y pueden no existir: sin ellos la
 * respuesta es un 403, no un error fatal. Tampoco se exige App\Models\User.
 */
class LoginAttemptPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {

        $exceptAbilities = [];

        if (method_exists($user, 'isAdmin') && $user->isAdmin() && !in_array($ability, $exceptAbilities)) {

            return true;

        }

    }

    public function index(Authenticatable $user)
    {
        return $this->allows($user, 'index', LoginAttempt::class);
    }

    public function viewAny(Authenticatable $user)
    {
        return $this->allows($user, 'viewAny', LoginAttempt::class);
    }

    public function view(Authenticatable $user, LoginAttempt $loginAttempt)
    {
        return $this->allows($user, 'view', $loginAttempt);
    }

    public function create(Authenticatable $user)
    {
        return $this->allows($user, 'create', LoginAttempt::class);
    }

    public function update(Authenticatable $user, LoginAttempt $loginAttempt)
    {
        return $this->allows($user, 'update', $loginAttempt);
    }

    public function delete(Authenticatable $user, LoginAttempt $loginAttempt)
    {
        return $this->allows($user, 'delete', $loginAttempt);
    }

    public function restore(Authenticatable $user, LoginAttempt $loginAttempt)
    {
        return $this->allows($user, 'restore', $loginAttempt);
    }

    public function forceDelete(Authenticatable $user, LoginAttempt $loginAttempt)
    {
        return $this->allows($user, 'forceDelete', $loginAttempt);
    }

    public function export(Authenticatable $user)
    {
        return $this->allows($user, 'export', LoginAttempt::class);
    }

    protected function allows(Authenticatable $user, string $ability, $target): bool
    {
        return method_exists($user, 'isAllowTo') && (bool) $user->isAllowTo($ability, $target);
    }

}
