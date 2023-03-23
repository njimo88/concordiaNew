<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class RoleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::directive('role', function ($permission) {
            return "<?php if(auth()->check() && auth()->user()->role->{$permission}): ?>";
        });

        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });
    }

    public function register()
    {
        //
    }
}
