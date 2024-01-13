<?php

namespace Innoboxrr\LaravelAudit\Policies;

use App\Models\User;
use Innoboxrr\LaravelAudit\Models\LoginAttempt;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoginAttemptPolicy
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
        return $user->isAllowTo('index', LoginAttempt::class);
    }

    public function viewAny(User $user)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('viewAny', LoginAttempt::class);
    }

    public function view(User $user, LoginAttempt $loginAttempt)
    {           
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('view', $loginAttempt);
    }

    public function create(User $user)
    {        
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('create', LoginAttempt::class);
    }

    public function update(User $user, LoginAttempt $loginAttempt)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('update', $loginAttempt);   
    }

    public function delete(User $user, LoginAttempt $loginAttempt)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('delete', $loginAttempt);
    }

    public function restore(User $user, LoginAttempt $loginAttempt)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('restore', $loginAttempt);
    }

    public function forceDelete(User $user, LoginAttempt $loginAttempt)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('forceDelete', $loginAttempt);
    }

    public function export(User $user)
    {
        if (!method_exists($user, 'isAllowTo')) return false;
        return $user->isAllowTo('export', LoginAttempt::class);
    }

}
