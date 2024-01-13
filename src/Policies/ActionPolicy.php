<?php

namespace Innoboxrr\LaravelAudit\Policies;

use App\Models\User;
use Innoboxrr\LaravelAudit\Models\Action;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActionPolicy
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
        return $user->isAllowTo('index', Action::class);
    }

    public function viewAny(User $user)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('viewAny', Action::class);
    }

    public function view(User $user, Action $action)
    {           
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('view', $action);
    }

    public function create(User $user)
    {        
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('create', Action::class);
    }

    public function update(User $user, Action $action)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('update', $action);   
    }

    public function delete(User $user, Action $action)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('delete', $action);
    }

    public function restore(User $user, Action $action)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('restore', $action);
    }

    public function forceDelete(User $user, Action $action)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('forceDelete', $action);
    }

    public function export(User $user)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('export', Action::class);
    }

}
