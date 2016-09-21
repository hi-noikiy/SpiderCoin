<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


class FrontendComposerServiceProvider extends ServiceProvider
{
    private $main, $menu, $user, $role, $permission;

    public function __construct()
    {
        $this->main = [
            'frontend.layout.sidebar',
            'Frontend.layout.breadcrumbs',
        ];

        $this->userInfo = [
            'frontend.layout.sidebar',
            'frontend.layout.header',
        ];
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer($this->userInfo, function ($view) {
            $userInfo = \Auth::user()->get();
            $view->with(compact('userInfo'));
        });

        view()->composer($this->main, 'App\Http\ViewComposers\MainComposer');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
