<?php

namespace Innoboxrr\LaravelAudit\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    public function boot()
    {

        $this->mapPolicies();
        
    }

    public function mapPolicies()
    {

        foreach (glob(__DIR__ . '/../Policies/*.php') as $file) {

            $policy = 'Innoboxrr\LaravelAudit\Policies\\' . substr(basename($file), 0, -4);
            
            $model = 'Innoboxrr\LaravelAudit\Models\\' . str_replace('Policy', '', $policy);

            if (class_exists($model) && class_exists($policy)) {
            
                Gate::policy($model, $policy);
            
            }

        }

    }

}