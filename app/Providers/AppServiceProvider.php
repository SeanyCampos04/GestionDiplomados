<?php

namespace App\Providers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $user_roles = Auth::check() ? Auth::user()->roles->pluck('nombre')->toArray() : [];
            $view->with('user_roles', $user_roles);

            $tipo_usuario = Auth::check() ? Auth::user()->tipo : null;
            $view->with('tipo_usuario', $tipo_usuario);
        });

    }
}
