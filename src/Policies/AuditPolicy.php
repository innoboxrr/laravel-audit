<?php

namespace Innoboxrr\LaravelAudit\Policies;

use App\Models\User;
use Innoboxrr\LaravelAudit\Models\Audit;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuditPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {

        $exceptAbilities = [];

        if($user->isAdmin() && !in_array($ability, $exceptAbilities)){
        
            return true;
            
        }

    }

    public function index(User $user)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('index', Audit::class);
    }

    public function viewAny(User $user)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('viewAny', Audit::class);
    }

    public function view(User $user, Audit $audit)
    {           
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('view', $audit);
    }

    public function create(User $user)
    {        
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('create', Audit::class);
    }

    public function update(User $user, Audit $audit)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('update', $audit);   
    }

    public function delete(User $user, Audit $audit)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('delete', $audit);
    }

    public function restore(User $user, Audit $audit)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('restore', $audit);
    }

    public function forceDelete(User $user, Audit $audit)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('forceDelete', $audit);
    }

    public function export(User $user)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('export', Audit::class);
    }

}
