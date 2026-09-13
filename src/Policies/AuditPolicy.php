<?php

namespace Innoboxrr\LaravelAudit\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Innoboxrr\LaravelAudit\Models\Audit;

/*
 * isAdmin() e isAllowTo() los pone el host y pueden no existir: sin ellos la
 * respuesta es un 403, no un error fatal. Tampoco se exige App\Models\User.
 */
class AuditPolicy
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
        return $this->allows($user, 'index', Audit::class);
    }

    public function viewAny(Authenticatable $user)
    {
        return $this->allows($user, 'viewAny', Audit::class);
    }

    public function view(Authenticatable $user, Audit $audit)
    {
        return $this->allows($user, 'view', $audit);
    }

    public function create(Authenticatable $user)
    {
        return $this->allows($user, 'create', Audit::class);
    }

    public function update(Authenticatable $user, Audit $audit)
    {
        return $this->allows($user, 'update', $audit);
    }

    public function delete(Authenticatable $user, Audit $audit)
    {
        return $this->allows($user, 'delete', $audit);
    }

    public function restore(Authenticatable $user, Audit $audit)
    {
        return $this->allows($user, 'restore', $audit);
    }

    public function forceDelete(Authenticatable $user, Audit $audit)
    {
        return $this->allows($user, 'forceDelete', $audit);
    }

    public function export(Authenticatable $user)
    {
        return $this->allows($user, 'export', Audit::class);
    }

    protected function allows(Authenticatable $user, string $ability, $target): bool
    {
        return method_exists($user, 'isAllowTo') && (bool) $user->isAllowTo($ability, $target);
    }

}
