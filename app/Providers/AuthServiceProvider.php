<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies();

        $gate->define('isSAdmin', function($user) {
            return $user->user_type_id == 's_admin';
        });

        $gate->define('isDean', function($user) {
            return $user->user_type_id == 'dean';
        });

        $gate->define('isProf', function($user) {
            return $user->user_type_id == 'prof';
        });

        $gate->define('isStud', function($user) {
            return $user->user_type_id == 'stud';
        });
    }
}
