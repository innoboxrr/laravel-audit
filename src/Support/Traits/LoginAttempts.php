<?php

namespace Innoboxrr\LaravelAudit\Support\Traits;

/**
 * Este trait lo debe implementar el modelo User
 */

use Innoboxrr\LaravelAudit\Models\LoginAttempt;

trait LoginAttempts 
{

	public function loginAttempts()
    {
        return $this->hasMany(LoginAttempt::class, 'email', 'email');
    }

    public function trackLoginAttempt($status = true)
    {
        LoginAttempt::create([
            'email' => $this->email,
            'status' => $status,
            // Las columnas no admiten null: un cliente sin User-Agent no debe
            // tumbar el login que se esta registrando.
            'ip_address' => (string) request()->ip(),
            'user_agent' => (string) request()->header('User-Agent'),
        ]);
    }

}