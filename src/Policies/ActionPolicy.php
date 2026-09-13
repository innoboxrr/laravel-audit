<?php

namespace Innoboxrr\LaravelAudit\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Innoboxrr\LaravelAudit\Models\Action;

/*
 * isAdmin() e isAllowTo() los pone el host y pueden no existir: sin ellos la
 * respuesta es un 403, no un error fatal. Tampoco se exige App\Models\User.
 */
class ActionPolicy
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
        return $this->allows($user, 'index', Action::class);
    }

    public function viewAny(Authenticatable $user)
    {
        return $this->allows($user, 'viewAny', Action::class);
    }

    public function view(Authenticatable $user, Action $action)
    {
        return $this->allows($user, 'view', $action);
    }

    public function create(Authenticatable $user)
    {
        return $this->allows($user, 'create', Action::class);
    }

    public function update(Authenticatable $user, Action $action)
    {
        return $this->allows($user, 'update', $action);
    }

    public function delete(Authenticatable $user, Action $action)
    {
        return $this->allows($user, 'delete', $action);
    }

    public function restore(Authenticatable $user, Action $action)
    {
        return $this->allows($user, 'restore', $action);
    }

    public function forceDelete(Authenticatable $user, Action $action)
    {
        return $this->allows($user, 'forceDelete', $action);
    }

    public function export(Authenticatable $user)
    {
        return $this->allows($user, 'export', Action::class);
    }

    protected function allows(Authenticatable $user, string $ability, $target): bool
    {
        return method_exists($user, 'isAllowTo') && (bool) $user->isAllowTo($ability, $target);
    }

}
